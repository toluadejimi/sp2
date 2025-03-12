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


        if (Auth::user()->status == 7) {
            return redirect('/bank-transfer')->with('error', "You can not make transfer at the moment, Please contact support");

        }

        $pos_trx = Feature::where('id', 1)->first()->pos_transfer ?? null;
        if ($pos_trx == 0) {
            return redirect('/bank-transfer')->with('error', "Transfer is not available at the moment, \n\n Please try again after some time");

        }

        $set = Setting::where('id', 1)->first();

        //TTMFB
        if ($set->bank == 'ttmfb') {

            $chk = FailedTransaction::where('user_id', Auth::id())->first() ?? null;
            $fa = FailedTransaction::where('user_id', Auth::id())->first() ?? null;

            if ($fa != null) {

                if ($chk->user_id == Auth::id()) {


                    $anchorTime = Carbon::createFromFormat("Y-m-d H:i:s", $fa->created_at);
                    $currentTime = Carbon::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:00"));
                    # count difference in minutes
                    $minuteDiff = $anchorTime->diffInMinutes($currentTime);


                    if ($minuteDiff >= 3) {
                        FailedTransaction::where('user_id', Auth::id())->delete();
                    }
                }
            }


            $fa = FailedTransaction::where('user_id', Auth::id())->first() ?? null;
            if ($fa != null) {
                if ($fa->attempt == 1) {
                    return redirect('/bank-transfer')->with('error', "Service not available at the moment, please wait for about 2 mins and try again");
                }

            }


            $userId = auth()->id();
            $timeLimit = now()->subMinutes(2);

            $duplicate = Transaction::where('user_id', $userId)
                ->where('amount', $amount)
                ->where('created_at', '>=', $timeLimit)
                ->exists();

            if ($duplicate) {
                return redirect('/bank-transfer')->with('error', "Likely duplicate transaction detected. Kindly hold on and try again later");

            }


            $referenceCode = trx();

            $transfer_charges = Charge::where('title', 'transfer_fee')->first()->amount;
            $bank_name = VfdBank::select('bankName')->where('code', $destinationBankCode)->first()->bankName ?? null;
            $amoutCharges = $amount + $transfer_charges;


            $ckid = PendingTransaction::where('user_id', Auth::id())->first()->user_id ?? null;
            if ($ckid == Auth::id()) {

                $message = Auth::user()->first_name . " " . Auth::user()->last_name . " | has reached this double endpoint";
                send_notification($message);

                return redirect('/bank-transfer')->with('error', "Please wait for some time and try again");

            }


            if (Auth::user()->status == 5) {

                return redirect('/bank-transfer')->with('error', "You can not make transfer at the moment, Please contact  support");

            }

            if (Auth::user()->status != 2) {

                $message = Auth::user()->first_name . " " . Auth::user()->last_name . " | Unverified Account trying withdraw";
                send_notification($message);

                return redirect('/bank-transfer')->with('error', "Please verify your account to enjoy enkpay full service");


            }


            $fa = FailedTransaction::where('user_id', Auth::id())->first() ?? null;
            if ($fa !== null) {


                if ($fa->attempt == 1) {

                    return redirect('/bank-transfer')->with('error', "Service not available at the moment, please wait and try again later");

                }
            }


            $user_email = user_email();
            $first_name = first_name();

            $description = $get_description ?? "Fund for $destinationAccountName";

            if ($wallet == 'main_account') {
                $user_wallet_banlance = main_account();
            } else {
                $user_wallet_banlance = bonus_account();
            }


            if ($amount < 100) {

                return redirect('/bank-transfer')->with('error', "Amount must not be less than NGN 100");
            }


            if ($amount > 1000000) {
                return redirect('/bank-transfer')->with('error', "You can not transfer more than NGN 1,000,000.00 at a time");

            }

            if (Auth()->user()->status == 1 && $amount > 20000) {
                return redirect('/bank-transfer')->with('error', "Please Complete your KYC");

            }


            if ($wallet == 'main_account') {


                if ($amoutCharges > Auth::user()->main_wallet) {

                    return redirect('/bank-transfer')->with('error', "Insufficient Funds, fund your main wallet");

                }
            } else {

                if ($amoutCharges > Auth::user()->bonus_wallet) {

                    return redirect('/bank-transfer')->with('error', "Insufficient Funds, fund your bonus wallet");

                }
            }

            if ($amoutCharges > $user_wallet_banlance) {

                return redirect('/bank-transfer')->with('error', "Insufficient Funds, fund your main wallet");

            }


            $status = 200;
            $enkpay_profit = $transfer_charges - 10;

            $under_id = User::where('id', Auth::id())->first()->register_under_id ?? null;
            if ($under_id != null) {

                $charge = SuperAgent::where('register_under_id', $under_id)->first()->transfer_charge ?? null;
                $get_transfer_charge = Charge::where('title', 'transfer_fee')->first()->amount;
                $scharge = $get_transfer_charge + $charge;

                $debited_amount = $scharge + $amount;

                $agent_user_id = SuperAgent::where('register_under_id', $under_id)->first()->user_id ?? null;
                $sbalance = User::where('id', $agent_user_id)->first()->main_wallet ?? null;

            } else {

                $debited_amount = $transfer_charges + $amount;

            }


            $chk_bal = ttmfb_balance() ?? 0;
            if ($chk_bal < $debited_amount) {

                $name = Auth::user()->first_name . " " . Auth::user()->last_name;
                $message = $name . "| Error " . "| insufficient funds " . number_format($chk_bal, 2);
                $result = "Message========> " . $message;
                send_notification($result);

                return response()->json([
                    'status' => $this->failed,
                    'message' => "Service not available at the moment, \n please wait and try again later",

                ], 500);
            }


            if ($status == 200) {

                $trans_id = guid();
                if ($wallet == 'main_account') {
                    User::where('id', Auth::id())->decrement('main_wallet', $debited_amount);
                } else {
                    User::where('id', Auth::id())->decrement('bonus_wallet', $debited_amount);
                }


                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $user_balance = $balance - $debited_amount;

                //update Transactions
                $trasnaction = new PendingTransaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->ref_trans_id = $referenceCode;
                $trasnaction->debit = $amoutCharges;
                $trasnaction->amount = $amount;
                $trasnaction->bank_code = $destinationBankCode;
                $trasnaction->enkpay_Cashout_profit = $enkpay_profit;
                $trasnaction->receiver_name = $destinationAccountName;
                $trasnaction->receiver_account_no = $destinationAccountNumber;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();


                $username = env('MUSERNAME');
                $prkey = env('MPRKEY');
                $sckey = env('MSCKEY');

                $unixTimeStamp = timestamp();
                $sha = sha512($unixTimeStamp . $prkey);
                $authHeader = 'magtipon ' . $username . ':' . base64_encode(hex2bin($sha));


                $ref = sha512($trans_id . $prkey);

                $signature = base64_encode(hex2bin($ref));
                $name = Auth::user()->first_name . " " . Auth::user()->last_name;


                $databody = array(

                    "Amount" => $amount,
                    "RequestRef" => $trans_id,
                    "CustomerDetails" => array(
                        "Fullname" => "ENKWAVE - ($name)",
                        "MobilePhone" => "",
                        "Email" => ""
                    ),
                    "BeneficiaryDetails" => array(
                        "Fullname" => "$receiver_name",
                        "MobilePhone" => "",
                        "Email" => ""
                    ),
                    "BankDetails" => array(
                        "BankType" => "comm",
                        "BankCode" => $destinationBankCode,
                        "AccountNumber" => $destinationAccountNumber,
                        "AccountType" => "10"
                    ),

                    "Signature" => $signature,
                );


                $post_data = json_encode($databody);


                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'http://magtipon.buildbankng.com/api/v1/transaction/fundstransfer',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $post_data,
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: $authHeader",
                        "Timestamp: $unixTimeStamp",
                        'Content-Type: application/json',
                    ),
                ));

                $var = curl_exec($curl);
                $result = json_decode($var);
                $status = $result->ResponseCode ?? null;
                $session_id = $result->RemoteRef ?? null;
                $tt_mfb_response = $result->TransactionRef ?? null;
                $api_ref = $result->RemoteRef ?? null;


                curl_close($curl);

                if ($status == 50003) {
                    $name = Auth::user()->first_name . " " . Auth::user()->last_name;
                    $ip = $request->ip();
                    $message = $name . "| Transfred " . $amount . " | " . $bank_name . " | " . $destinationAccountNumber . "Duplicate Transaction";
                    $result = "Message========> " . $message . "\n\nIP========> " . $ip;
                    send_notification($result);

                    return redirect('/bank-transfer')->with('error', "Duplicate Transaction");

                }

                if ($status == 11011 || $status == 50002) {

                    $trasnaction = new Transaction();
                    $trasnaction->user_id = Auth::id();
                    $trasnaction->ref_trans_id = $referenceCode;
                    $trasnaction->e_ref = $tt_mfb_response;
                    $trasnaction->ttmfb_api_ref = $api_ref;
                    $trasnaction->type = "InterBankTransfer";
                    $trasnaction->main_type = "Transfer";
                    $trasnaction->transaction_type = "BankTransfer";
                    $trasnaction->title = "Bank Transfer";
                    $trasnaction->debit = $amoutCharges;
                    $trasnaction->amount = $amount;
                    $trasnaction->note = "PENDING - BANK TRANSFER TO | $receiver_name | $destinationAccountNumber  \n $session_id ";
                    $trasnaction->fee = 0;
                    $trasnaction->receiver_name = $destinationAccountName;
                    $trasnaction->p_sessionid = $session_id;
                    $trasnaction->receiver_account_no = $destinationAccountNumber;
                    $trasnaction->balance = $balance;
                    $trasnaction->status = 0;
                    $trasnaction->save();

                    //Transfers
                    $trasnaction = new Transfer();
                    $trasnaction->user_id = Auth::id();
                    $trasnaction->ref_trans_id = $referenceCode;
                    $trasnaction->e_ref = $tt_mfb_response;
                    $trasnaction->type = "TTBankTransfer";
                    $trasnaction->main_type = "Transfer";
                    $trasnaction->transaction_type = "BankTransfer";
                    $trasnaction->title = "Bank Transfer";
                    $trasnaction->debit = $amoutCharges;
                    $trasnaction->amount = $amount;
                    $trasnaction->note = "PENDING - BANK TRANSFER TO | $receiver_name | $destinationAccountNumber  \n $session_id ";
                    $trasnaction->receiver_name = $receiver_name;
                    $trasnaction->receiver_account_no = $destinationAccountNumber;
                    $trasnaction->receiver_bank = $bank_name;
                    $trasnaction->balance = $balance;
                    $trasnaction->status = 1;
                    $trasnaction->longitude = $longitude;
                    $trasnaction->latitude = $latitude;
                    $trasnaction->save();


                    $email = new EmailSend();
                    $email->receiver_email = Auth::user()->email;
                    $email->amount = $amount;
                    $email->first_name = $first_name;
                    $email->save();

                    $wallet = $balance;
                    $name = Auth::user()->first_name . " " . Auth::user()->last_name;
                    $ip = $request->ip();
                    $message = $name . "PENDING | Transfer " . $amount . " | " . $bank_name . " | " . $destinationAccountNumber . " User balance | " . number_format($balance, 2) . "| Status - Pending";
                    $result = "Message========> " . $message . "\n\nIP========> " . $ip;
                    send_notification($result);

                        PendingTransaction::where('user_id', Auth::id())->delete() ?? null;

                    return redirect('/bank-transfer')->with('message', "Transaction Processed");

                }


                if ($status == 90000) {
                    //update Transactions
                    $trasnaction = new Transaction();
                    $trasnaction->user_id = Auth::id();
                    $trasnaction->ttmfb_api_ref = $api_ref;
                    $trasnaction->ref_trans_id = $referenceCode;
                    $trasnaction->e_ref = $tt_mfb_response;
                    $trasnaction->type = "InterBankTransfer";
                    $trasnaction->main_type = "Transfer";
                    $trasnaction->transaction_type = "BankTransfer";
                    $trasnaction->title = "Bank Transfer";
                    $trasnaction->debit = $amoutCharges;
                    $trasnaction->amount = $amount;
                    $trasnaction->note = "BANK TRANSFER TO | $receiver_name | $destinationAccountNumber  \n $session_id  ";
                    $trasnaction->fee = 0;
                    $trasnaction->enkpay_Cashout_profit = $enkpay_profit;
                    $trasnaction->receiver_name = $destinationAccountName;
                    $trasnaction->receiver_account_no = $destinationAccountNumber;
                    $trasnaction->p_sessionid = $session_id;
                    $trasnaction->balance = $balance;
                    $trasnaction->status = 1;
                    $trasnaction->save();

                    //Transfers
                    $trasnaction = new Transfer();
                    $trasnaction->user_id = Auth::id();
                    $trasnaction->ref_trans_id = $referenceCode;
                    $trasnaction->e_ref = $tt_mfb_response;
                    $trasnaction->type = "TTBankTransfer";
                    $trasnaction->main_type = "Transfer";
                    $trasnaction->transaction_type = "BankTransfer";
                    $trasnaction->title = "Bank Transfer";
                    $trasnaction->debit = $amoutCharges;
                    $trasnaction->amount = $amount;
                    $trasnaction->note = "BANK TRANSFER TO | $receiver_name | $destinationAccountNumber | $$session_id  ";
                    $trasnaction->bank_code = $destinationBankCode;
                    $trasnaction->enkpay_Cashout_profit = $enkpay_profit;
                    $trasnaction->receiver_name = $receiver_name;
                    $trasnaction->receiver_account_no = $destinationAccountNumber;
                    $trasnaction->receiver_bank = $bank_name;
                    $trasnaction->balance = $balance;
                    $trasnaction->status = 1;
                    $trasnaction->save();


                    $under_id = User::where('id', Auth::id())->first()->register_under_id ?? null;
                    if ($under_id != null) {
                        User::where('id', $agent_user_id)->increment('main_wallet', (int)$charge);
                        //Agent
                        $trasnaction = new Transaction();
                        $trasnaction->user_id = $agent_user_id ?? 0;
                        $trasnaction->ref_trans_id = $referenceCode;
                        $trasnaction->transaction_type = "BankTransfer";
                        $trasnaction->credit = $charge;
                        $trasnaction->title = "Commission";
                        $trasnaction->note = "ENKPAY Transfer | Commission";
                        $trasnaction->amount = $charge;
                        $trasnaction->balance = $sbalance;
                        $trasnaction->status = 1;
                        $trasnaction->save();
                    }


                    $email = new EmailSend();
                    $email->receiver_email = Auth::user()->email;
                    $email->amount = $amount;
                    $email->first_name = $first_name;
                    $email->save();


                    //Beneficiary
                    $ck = Beneficiary::where([
                        'bank_code' => $destinationBankCode,
                        'acct_no' => $destinationAccountNumber,
                        'user_id' => Auth::id(),
                    ])->first() ?? null;


                    if ($ck == null) {
                        $ben = new Beneficiary();
                        $ben->name = $destinationAccountName;
                        $ben->bank_code = $destinationBankCode;
                        $ben->acct_no = $destinationAccountNumber;
                        $ben->user_id = Auth::id();
                        $ben->save();
                    }


                    $wallet = Auth::user()->main_wallet - $amount;
                    $name = Auth::user()->first_name . " " . Auth::user()->last_name;
                    $ip = $request->ip();
                    $message = $name . "| Transfred " . $amount . " | " . $bank_name . " | " . $destinationAccountNumber . " User balance | " . number_format($user_balance, 2);
                    $result = "Message========> " . $message . "\n\nIP========> " . $ip;
                    send_notification($result);


                        PendingTransaction::where('user_id', Auth::id())->delete() ?? null;

                    return view('web.transfer.transaction-success', compact('amount'))->with('message', "Transaction Completed.");


                }


                if ($wallet == 'main_account') {

                    $transfer_charges = Charge::where('title', 'transfer_fee')->first()->amount;
                    $User_wallet_banlance = User::where('id', Auth::id())->first()->main_wallet;

                    $credit = $User_wallet_banlance + $amount + $transfer_charges;
                    $update = User::where('id', Auth::id())
                        ->update([
                            'main_wallet' => $credit,
                        ]);
                } else {

                    $transfer_charges = Charge::where('title', 'transfer_fee')->first()->amount;
                    $User_wallet_banlance = User::where('id', Auth::id())->first()->bonus_wallet;

                    $credit = $User_wallet_banlance + $amount + $transfer_charges;
                    $update = User::where('id', Auth::id())
                        ->update([
                            'bonus_wallet' => $credit,
                        ]);
                }


                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->ref_trans_id = $referenceCode;
                $trasnaction->transaction_type = "Reversal";
                $trasnaction->debit = 0;
                $trasnaction->amount = $amount;
                $trasnaction->credit = $amount;
                $trasnaction->serial_no = 0;
                $trasnaction->title = "Reversal";
                $trasnaction->note = "Reversal";
                $trasnaction->fee = 25;
                $trasnaction->balance = $credit;
                $trasnaction->main_type = "Reversal";
                $trasnaction->status = 3;
                $trasnaction->save();

                    PendingTransaction::where('user_id', Auth::id())->delete() ?? null;


                if ($status == 60001) {

                    $usr = User::where('id', Auth::id())->first();
                    $message = "Transaction reversed | $status | " . $result->ResponseDescription ?? null;
                    $full_name = $usr->first_name . "  " . $usr->last_name;

                    $result = $status . "| Message========> " . $message . "\n\nCustomer Name========> " . $full_name;
                    send_notification($result);

                    return redirect('/bank-transfer')->with('message', "Transaction Reversed");


                }

                $usr = User::where('id', Auth::id())->first();
                $message = "Transaction reversed | $status";
                $full_name = $usr->first_name . "  " . $usr->last_name;

                $result = $status . "| Message========> " . $message . "\n\nCustomer Name========> " . $full_name;
                send_notification($result);


                return redirect('/bank-transfer')->with('message', "Transaction Processed");


            }
        }

        //WOVEN
        if ($set->bank == 'woven') {


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
            $name = Auth::user()->first_name . " " . Auth::user()->last_name;


            $chk = FailedTransaction::where('user_id', Auth::id())->first() ?? null;
            $fa = FailedTransaction::where('user_id', Auth::id())->first() ?? null;

            if ($fa != null) {

                if ($chk->user_id == Auth::id()) {
                    $anchorTime = Carbon::createFromFormat("Y-m-d H:i:s", $fa->created_at);
                    $currentTime = Carbon::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:00"));
                    $minuteDiff = $anchorTime->diffInMinutes($currentTime);

                    if ($minuteDiff >= 3) {
                        FailedTransaction::where('user_id', Auth::id())->delete();
                    }
                }
            }


            $fa = FailedTransaction::where('user_id', Auth::id())->first() ?? null;
            if ($fa != null) {

                if ($fa->attempt == 1) {
                    return redirect('/bank-transfer')->with('error', "Service not available at the moment, please wait for about 2 mins and try again");
                }
            }


            $fa = FailedTransaction::where('user_id', Auth::id())->first() ?? null;
            if ($fa != null) {

                if ($fa->attempt == 1) {

                    return redirect('/bank-transfer')->with('error', "Service not available at the moment, please wait for about 2 mins and try again");

                }
            }


            $userId = auth()->id();
            $timeLimit = now()->subMinutes(2);

            $duplicate = Transaction::where('user_id', $userId)
                ->where('amount', $amount)
                ->where('created_at', '>=', $timeLimit)
                ->exists();

            if ($duplicate) {

                return redirect('/bank-transfer')->with('error', "Likely duplicate transaction detected. Kindly hold on and try again later.");


            }


            $referenceCode = trx();

            $transfer_charges = Charge::where('title', 'transfer_fee')->first()->amount;
            $bank_name = VfdBank::select('bankName')->where('code', $destinationBankCode)->first()->bankName ?? null;
            $amoutCharges = $amount + $transfer_charges;


            $ckid = PendingTransaction::where('user_id', Auth::id())->first()->user_id ?? null;
            if ($ckid == Auth::id()) {

                $message = Auth::user()->first_name . " " . Auth::user()->last_name . " | has reached this double endpoint";
                send_notification($message);

                return redirect('/bank-transfer')->with('error', "Please wait for some time and try again.");

            }


            if (Auth::user()->status == 5) {

                return redirect('/bank-transfer')->with('error', "You can not make transfer at the moment, Please contact  support.");

            }

            if (Auth::user()->status != 2) {

                $message = Auth::user()->first_name . " " . Auth::user()->last_name . " | Unverified Account trying withdraw";
                send_notification($message);

                return redirect('/bank-transfer')->with('error', "Please verify your account to enjoy enkpay full service.");

            }


            $fa = FailedTransaction::where('user_id', Auth::id())->first() ?? null;
            if ($fa !== null) {


                if ($fa->attempt == 1) {

                    return redirect('/bank-transfer')->with('error', "Service not available at the moment, please wait and try again later.");
                }
            }


            $user_email = user_email();
            $first_name = first_name();

            $description = $get_description ?? "Fund for $destinationAccountName";

            if ($wallet == 'main_account') {
                $user_wallet_banlance = main_account();
            } else {
                $user_wallet_banlance = bonus_account();
            }


            if (Auth::user()->b_number == 6) {

                return redirect('/bank-transfer')->with('error', "You dont have the permission to make transfer.");

            }

            if ($amount < 100) {

                return redirect('/bank-transfer')->with('error', "Amount must not be less than NGN 100r.");

            }


            if ($amount > 1000000) {

                return redirect('/bank-transfer')->with('error', "You can not transfer more than NGN 1,000,000.00 at a time.");

            }

            if (Auth()->user()->status == 1 && $amount > 20000) {

                return redirect('/bank-transfer')->with('error', "Please Complete your KYC.");

            }


            if ($wallet == 'main_account') {


                if ($amoutCharges > Auth::user()->main_wallet) {

                    return redirect('/bank-transfer')->with('error', "Insufficient Funds, fund your main wallet");

                }
            } else {

                if ($amoutCharges > Auth::user()->bonus_wallet) {
                    return redirect('/bank-transfer')->with('error', "Insufficient Funds, fund your main wallet.");

                }
            }

            if ($amoutCharges > $user_wallet_banlance) {

                return redirect('/bank-transfer')->with('error', "Insufficient Funds, fund your main wallet.");


            }


            $status = 200;
            $enkpay_profit = $transfer_charges - 10;

            $under_id = User::where('id', Auth::id())->first()->register_under_id ?? null;
            if ($under_id != null) {

                $charge = SuperAgent::where('register_under_id', $under_id)->first()->transfer_charge ?? null;
                $get_transfer_charge = Charge::where('title', 'transfer_fee')->first()->amount;
                $scharge = $get_transfer_charge + $charge;

                $debited_amount = $scharge + $amount;

                $agent_user_id = SuperAgent::where('register_under_id', $under_id)->first()->user_id ?? null;
                $sbalance = User::where('id', $agent_user_id)->first()->main_wallet ?? null;

            } else {

                $debited_amount = $transfer_charges + $amount;

            }


            $chk_bal = woevn_balance() ?? 0;

            if ($chk_bal < $debited_amount) {

                $name = Auth::user()->first_name . " " . Auth::user()->last_name;
                $message = $name . "| Error " . "| insufficient funds " . number_format($chk_bal, 2);
                $result = "Message========> " . $message;
                send_notification($result);

                return redirect('/bank-transfer')->with('error', "000 Service not available at the moment, \n please wait and try again later.");

            }


            if ($status == 200) {

                $trans_id = guid();
                //Debit

                if ($wallet == 'main_account') {
                    User::where('id', Auth::id())->decrement('main_wallet', $debited_amount);

                } else {
                    User::where('id', Auth::id())->decrement('bonus_wallet', $debited_amount);

                }


                $balance = User::where('id', Auth::id())->first()->main_wallet;
                $user_balance = $balance - $debited_amount;

                //update Transactions
                $trasnaction = new PendingTransaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->ref_trans_id = $referenceCode;
                $trasnaction->debit = $amoutCharges;
                $trasnaction->amount = $amount;
                $trasnaction->bank_code = $destinationBankCode;
                $trasnaction->enkpay_Cashout_profit = $enkpay_profit;
                $trasnaction->receiver_name = $destinationAccountName;
                $trasnaction->receiver_account_no = $destinationAccountNumber;
                $trasnaction->sender_name = $name;
                $trasnaction->balance = $balance;
                $trasnaction->longitude = $longitude;
                $trasnaction->latitude = $latitude;
                $trasnaction->status = 0;
                $trasnaction->save();


                $trasnaction = new Transaction();
                $trasnaction->user_id = Auth::id();
                $trasnaction->ref_trans_id = $referenceCode;
                $trasnaction->type = "InterBankTransfer";
                $trasnaction->main_type = "Transfer";
                $trasnaction->transaction_type = "BankTransfer";
                $trasnaction->title = "Bank Transfer";
                $trasnaction->debit = $amoutCharges;
                $trasnaction->amount = $amount;
                $trasnaction->note = "BANK TRANSFER TO | $destinationAccountName | $destinationAccountNumber";
                $trasnaction->fee = 0;
                $trasnaction->enkpay_Cashout_profit = 15;
                $trasnaction->receiver_name = $destinationAccountName;
                $trasnaction->receiver_account_no = $destinationAccountNumber;
                $trasnaction->longitude = $longitude;
                $trasnaction->latitude = $latitude;
                $trasnaction->balance = $balance;
                $trasnaction->status = 0;
                $trasnaction->save();


                if ($trasnaction) {


                    $balace_check = woevn_balance();
                    $message = $request->payout_referenc . " Transaction initiated |  Int Bal" . $balace_check;
                    send_notification($message);


                    $account = Setting::where('id', 1)->first()->r_account;
                    $api = env('WOVENKEY');
                    $trx_ck = PendingTransaction::where('status', 0)->count() ?? null;
                    $bank = Setting::where('id', 1)->first()->bank ?? null;

                    $databody = array(
                        "source_account" => $account,
                        "PIN" => env('WOVENPIN'),
                        "beneficiary_account_name" => $destinationAccountName,
                        "beneficiary_nuban" => $destinationAccountNumber,
                        "beneficiary_bank_code" => $destinationBankCode,
                        "bank_code_scheme" => "NIP",
                        "currency_code" => "NGN",
                        "narration" => "Bank Transfer to $destinationAccountName",
                        "amount" => $amount,
                        "reference" => $referenceCode,
                        "sender_name" => $name,
                        "callback_url" => url('') . "/api/transfer-webhook",
                        "meta_data" => array(
                            "beneficiary_phone" => "08033212933",
                            "beneficiary_email" => "johndoe@testme.com",
                        ),
                    );

                    $post_data = json_encode($databody);
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.woven.finance/v2/api/payouts/request?command=initiate',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $post_data,
                        CURLOPT_HTTPHEADER => array(
                            "api_secret: $api",
                            'Content-Type: application/json',
                        ),
                    ));

                    $var = curl_exec($curl);
                    $result = json_decode($var);
                    $status = $result->success ?? null;
                    $error = $result->status ?? null;
                    $message = $result->message ?? null;
                    curl_close($curl);

                    if ($status == "success" && $message == "Payout transaction successful") {

                        Transaction::where('ref_trans_id', $referenceCode)->where('status', 0)->update([
                            'e_ref' => $result->data->unique_reference,
                            'p_sessionid' => $result->data->nip_session_id,
                            'status' => 1,
                        ]);


                        return view('web.transfer.transaction-success', compact('amount'))->with('message', "Transaction Completed.");


                    } elseif ($error == "error") {

                        $usr = User::where('id', Auth::id())->first();
                        $message = "Woven Transfer Error ===>>>>  | " . json_encode($result) . "\n\n" . "Trx_id = $referenceCode";
                        $full_name = $usr->first_name . "  " . $usr->last_name;
                        $result = $status . "| Message========> " . $message . "\n\nCustomer Name========> " . $full_name;
                        send_notification($result);


                    } else {

                        $usr = User::where('id', Auth::id())->first();
                        $message = "Woven Transfer Error ===>>>>  | " . json_encode($result) . "\n\n" . "Trx_id = $referenceCode";
                        $full_name = $usr->first_name . "  " . $usr->last_name;
                        $result = $status . "| Message========> " . $message . "\n\nCustomer Name========> " . $full_name;
                        send_notification($result);


                    }


                    PendingTransaction::where('user_id', Auth::id())->delete() ?? null;

                    $email = new EmailSend();
                    $email->receiver_email = Auth::user()->email;
                    $email->amount = $amount;
                    $email->first_name = $first_name;
                    $email->save();


                    //Beneficiary


                    $ck = Beneficiary::where([
                        'bank_code' => $destinationBankCode,
                        'acct_no' => $destinationAccountNumber,
                        'user_id' => Auth::id(),
                    ])->first() ?? null;


                    if ($ck == null) {
                        $ben = new Beneficiary();
                        $ben->name = $destinationAccountName;
                        $ben->bank_code = $destinationBankCode;
                        $ben->acct_no = $destinationAccountNumber;
                        $ben->user_id = Auth::id();
                        $ben->save();
                    }


                    $wallet = Auth::user()->main_wallet - $amount;
                    $name = Auth::user()->first_name . " " . Auth::user()->last_name;
                    $ip = $request->ip();
                    $new_bal = woevn_balance();
                    $message = $name . "| Transfer Initiated " . $amount . " | " . $bank_name . " | " . $destinationAccountNumber . " User balance | " . number_format($balance, 2) . "\n\n New Wov bal - " . $new_bal;
                    $result = "Message========> " . $message . "\n\nIP========> " . $ip;
                    send_notification($result);

                    return view('web.transfer.transaction-success', compact('amount'))->with('message', "Transaction Completed.");

                }


                return redirect('/bank-transfer')->with('message', "Transaction Processed.");



            }



        }


    }

    public function set_pin_page(request $request)
    {

        return view('web.transfer.set-pin');


    }

    public function set_pin(request $request)
    {

        $pin = $request->pin1 . $request->pin2 . $request->pin3 . $request->pin4;
        $set_pin = User::where('id', Auth::id())->update(['pin' => bcrypt($pin)]);
        if ($set_pin) {
            return redirect('/bank-transfer')->with('message', "Transfer Pin set successfully");
        }

    }


    public function open_transaction(request $request)
    {

        $trx = Transaction::where('id', $request->id)->first() ?? null;
        if ($trx == null) {
            return back()->with('error', 'Transaction not found');
        }

        return view('web.history.open-trx', compact('trx'));

    }

    public function transaction_successful(request $request)
    {
        $amount = $request->amount;
        return view('web.transfer.transaction-success', compact('amount'));

    }



}
