<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\Charge;
use App\Models\Setting;
use App\Models\SuperAgent;
use App\Models\Transfer;
use App\Models\User;
use App\Models\WebsiteTransfer;
use App\Models\Webtransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TransferController extends Controller
{
    public function bank_transfer_index()
    {

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

        return view('web.transfer.index', $data);

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
        return view('web.transfer.preview', compact( 'data'));


    }





    public function transfer_now(request $request)
    {

        $pin = $request->pin1.$request->pin2.$request->pin3.$request->pin4;
        $user_pin = Auth()->user()->pin;
        if (Hash::check($pin, $user_pin) == false) {
            return back()->with('error', "Incorrect Pin");
        }


    }

}
