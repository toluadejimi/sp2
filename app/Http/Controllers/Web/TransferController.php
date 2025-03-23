<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\Charge;
use App\Models\EmailSend;
use App\Models\FailedTransaction;
use App\Models\Feature;
use App\Models\PendingTransaction;
use App\Models\Setting;
use App\Models\SuperAgent;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\User;
use App\Models\VfdBank;
use App\Models\WebsiteTransfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class TransferController extends Controller
{
    public function bank_transfer_index()
    {

        $set = Setting::where('id', 1)->first();

        if ($set->bank == 'ttmfb') {

            $ck_pin = User::where('id', Auth::id())->first()->pin ?? null;

            if ($ck_pin == null) {
                return view('web.transfer.set-pin');
            }

            $set = Setting::where('id', 1)->first();
            if ($set->bank == 'vfd') {
                $data = 'vfd';
            } else {
                $data = 'pbank';
            }

            $banks = get_banks($data);
            $account = select_account();
            $under_id = User::where('id', Auth::id())->first()->register_under_id ?? null;
            $charge = SuperAgent::where('register_under_id', $under_id)->first()->transfer_charge ?? null;

            if ($under_id != null) {

                $get_transfer_charge = Charge::where('title', 'transfer_fee')
                    ->first()->amount;
                $ggtransfer_charge = $get_transfer_charge + $charge;
                $transfer_charge = strval($ggtransfer_charge);


            } else {

                $transfer_charge = Charge::where('title', 'transfer_fee')
                    ->first()->amount;

            }

            $data = [];

            $bens = Beneficiary::select('id', 'name', 'bank_code', 'acct_no')->where('user_id', Auth::id())->get() ?? [];

            $data['account'] = $account;
            $data['transfer_charge'] = $transfer_charge;
            $data['banks'] = $banks;
            $data['beneficariy'] = $bens;
            $data['baanky'] = $set->bank;


            return view('web.transfer.index', $data);


        }


        if ($set->bank == 'woven') {

            $ck_pin = User::where('id', Auth::id())->first()->pin ?? null;

            if ($ck_pin == null) {
                return view('web.transfer.set-pin');
            }

            $set = Setting::where('id', 1)->first();
            if ($set->bank == 'vfd') {
                $data = 'vfd';
            } else {
                $data = 'pbank';
            }

            $banks = get_banks($data);

            $account = select_account();
            $under_id = User::where('id', Auth::id())->first()->register_under_id ?? null;
            $charge = SuperAgent::where('register_under_id', $under_id)->first()->transfer_charge ?? null;

            if ($under_id != null) {

                $get_transfer_charge = Charge::where('title', 'transfer_fee')
                    ->first()->amount;
                $ggtransfer_charge = $get_transfer_charge + $charge;
                $transfer_charge = strval($ggtransfer_charge);


            } else {

                $transfer_charge = Charge::where('title', 'transfer_fee')
                    ->first()->amount;

            }

            $data = [];

            $bens = Beneficiary::select('id', 'name', 'bank_code', 'acct_no')->where('user_id', Auth::id())->get() ?? [];

            $data['account'] = $account;
            $data['transfer_charge'] = $transfer_charge;
            $data['banks'] = $banks;
            $data['beneficariy'] = $bens;
            $data['baanky'] = $set->bank;

            return view('web.transfer.index', $data);


        }


    }


    public function process_bank_transfer(request $request)
    {


        $amount = preg_replace('/[^\d]/', '', $request->input('amount'));
        $trx = new WebsiteTransfer();
        $trx->funds_account = $request->funds_account;
        $trx->bank_code = $request->bank_code;
        $trx->acct_no = $request->acct_no;
        $trx->acct_name = $request->acct_name;
        $trx->user_id = Auth::id();
        $trx->amount = $amount;
        $trx->narattion = $request->acct_name;
        $trx->bank_name = $request->bank_name;
        $trx->save();


        $data = WebsiteTransfer::where('id', $trx->id)->first();
        return view('web.transfer.preview', compact('data'));


    }


    public function transfer_now(request $request)
    {

        $pin = $request->pin1 . $request->pin2 . $request->pin3 . $request->pin4;
        $user_pin = Auth()->user()->pin;
        if (Hash::check($pin, $user_pin) == false) {
            return redirect('/bank-transfer')->with('error', "Incorrect Pin");
        }

        $tranx = WebsiteTransfer::where('id', $request->id)->first();
        $wallet = $tranx->funds_account;
        $amount = $tranx->amount;
        $destinationAccountNumber = $tranx->acct_no;
        $destinationBankCode = $tranx->bank_code;
        $destinationAccountName = $tranx->acct_name;
        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $receiver_name = $tranx->acct_name;
        $get_description = $tranx->narattion;
        $beneficiary = Auth::user()->first_name . " " . Auth::user()->last_name;


        $token = Auth::user()->token;
        $databody = array(

            'wallet' => $wallet,
            'amount' => $amount,
            'account_number' => $destinationAccountNumber,
            'code' => $destinationBankCode,
            'customer_name' => $destinationAccountName,
            'narration' => $get_description,
            'pin' => $pin,

        );

        $body = json_encode($databody);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://enkpayapp.enkwave.com/api/bank-transfer',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                "Authorization: Bearer $token",
                'Cookie: enkpay_session=BpFzgsfLPk3KWsnFabmvGOEelziHQpK9TyayW6so'
            ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);
        $status = $var->status ?? null;
        $message = $var->message ?? "Something went wrong"; // Default fallback
        $amount = $var->amount ?? 0; // Ensure $amount is defined

        if ($message === "Unauthenticated.") {
            User::where('id', Auth::id())->update(['token' => null]);
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your session has expired. Please login again.');
        }

        if ($status === false) {
            return redirect('bank-transfer')->with('error', $message);
        } elseif ($status === true) {
            $amount = $tranx->amount;

            $emailSettings = Setting::first();
            if ($emailSettings) {
                Config::set('mail.mailers.smtp.host', $emailSettings->mail_host);
                Config::set('mail.mailers.smtp.port', $emailSettings->mail_port);
                Config::set('mail.mailers.smtp.encryption', $emailSettings->mail_encryption);
                Config::set('mail.mailers.smtp.username', $emailSettings->mail_username);
                Config::set('mail.mailers.smtp.password', $emailSettings->mail_password);
                Config::set('mail.from.address', $emailSettings->mail_from_address);
                Config::set('mail.from.name', $emailSettings->mail_from_name);
            }

            $data = array(
                'fromsender' => 'noreply@enkpay.com', 'SprintPay',
                'subject' => "Transfer Notification",
                'amount' => $amount,
                'user' => Auth::user()->first_name,
                'toreceiver' => Auth::user()->email,
            );

            Mail::send('emails.transfer', ["data1" => $data], function ($message) use ($data) {
                $message->from($data['fromsender']);
                $message->to($data['toreceiver']);
                $message->subject($data['subject']);
            });





            return view('web.transfer.transaction-success', compact('amount'));
        } else {
            return redirect('bank-transfer')->with('error', "Something went wrong");
        }





    }






public
function set_pin_page(request $request)
{

    return view('web.transfer.set-pin');


}

public
function set_pin(request $request)
{

    $pin = $request->pin1 . $request->pin2 . $request->pin3 . $request->pin4;
    $set_pin = User::where('id', Auth::id())->update(['pin' => bcrypt($pin)]);
    if ($set_pin) {
        return redirect('/bank-transfer')->with('message', "Transfer Pin set successfully");
    }

}


public
function open_transaction(request $request)
{

    $trx = Transaction::where('id', $request->id)->first() ?? null;
    if ($trx == null) {
        return back()->with('error', 'Transaction not found');
    }

    return view('web.history.open-trx', compact('trx'));

}

public
function transaction_successful(request $request)
{
    $amount = $request->amount;
    return view('web.transfer.transaction-success', compact('amount'));
}


}
