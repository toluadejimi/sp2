<?php

use App\Models\AccountInfo;
use App\Models\ErrandKey;
use App\Models\ProvidusBank;
use App\Models\Setting;
use App\Models\Terminal;
use App\Models\TidConfig;
use App\Models\Ttmfb;
use App\Models\User;
use App\Models\VfdBank;
use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


if (!function_exists('main_account')) {


    function main_account()
    {
        $user = Auth::user();
        return $user->main_wallet;
    }
}

if (!function_exists('user_status')) {

    function user_status()
    {
        $user = Auth::user();
        return $user->status;
    }
}

if (!function_exists('bonus_account')) {

    function bonus_account()
    {
        $user = Auth::user();
        return $user->bonus_wallet;
    }
}

if (!function_exists('user_email')) {

    function user_email()
    {
        $user = Auth::user();
        return $user->email;
    }
}

if (!function_exists('user_phone')) {

    function user_phone()
    {
        $user = Auth::user();
        return $user->phone;
    }
}

if (!function_exists('user_bvn')) {

    function user_bvn()
    {
        $user = Auth::user();
        return $user->bvn;
    }
}

if (!function_exists('first_name')) {

    function first_name()
    {
        $user = Auth::user();
        return $user->first_name;
    }
}

if (!function_exists('last_name')) {

    function last_name()
    {
        $user = Auth::user();
        return $user->last_name;
    }
}

if (!function_exists('user_status')) {

    function user_status()
    {
        $user = Auth::user();
        return $user->status;
    }
}

if (!function_exists('select_account')) {

    function select_account()
    {


        $account = User::where('id', Auth::id())->first();

        //dd($account);

        $account_array = array();
        $account_array[0] = [
            "title" => "Main Account",
            "amount" => $account->main_wallet,
            "key" => "main_account",

        ];
        $account_array[1] = [
            "title" => "Bonus Account",
            "amount" => $account->bonus_wallet,
            "key" => "bonus_account",
        ];

        return $account_array;
    }
}


if (!function_exists('user_virtual_account_list')) {

    function virtual_account()
    {


        $account = VirtualAccount::where('user_id', Auth::id())->get() ?? null;


        if ($account !== null) {


            foreach ($account as $item) {
                $account_array[] = array(
                    "bank_name" => $item['v_bank_name'],
                    "account_no" => $item['v_account_no'],
                    "account_name" => $item['v_account_name'],
                );
            }

            // $account_array = array();
            // $account_array[0] = [
            //     "bank_name" => $account[0]['v_bank_name'],
            //     "account_no" => $account[0]['v_account_no'],
            //     "account_name" => $account[0]['v_account_name'],

            // ];


            // $account_array[1] = [
            //     "bank_name" => $account[1]['v_bank_name'],
            //     "account_no" => $account[1]['v_account_no'],
            //     "account_name" => $account[1]['v_account_name'],
            // ];


            return $account_array ?? [];
        }


        return [];
    }
}


if (!function_exists('terminal_info')) {


    function terminal_info()
    {
        $tm = Terminal::select('merchantNo', 'terminalNo', 'merchantName', 'deviceSN')->where('user_id', Auth::id())->first() ?? null;
        if ($tm != null) {
            return $tm;
        }
        return $tm;
    }
}


if (!function_exists('tid_config')) {
    function tid_config()
    {
        $tm = TidConfig::select('ip', 'port', 'ssl', 'compKey1', 'compKey2', 'baseUrl', 'logoUrl')->where('user_id', Auth::id())->first() ?? null;
        if ($tm != null) {
            return $tm;
        }
        return $tm;
    }
}


if (!function_exists('send_error')) {

    function send_error($message)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.telegram.org/bot6140179825:AAGfAmHK6JQTLegsdpnaklnhBZ4qA1m2c64/sendMessage?chat_id=1316552414',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'chat_id' => "1316552414",
                'text' => $message,

            ),
            CURLOPT_HTTPHEADER => array(),
        ));

        $var = curl_exec($curl);
        curl_close($curl);

        $var = json_decode($var);
    }
}


function send_notification($message)
{

    $boturl = env('BOTURL');
    $chat_id = env('BOTID');

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $boturl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'chat_id' => $chat_id,
            'text' => $message,

        ),
        CURLOPT_HTTPHEADER => array(),
    ));

    $var = curl_exec($curl);
    curl_close($curl);

    $var = json_decode($var);
}

if (!function_exists('trx')) {

    function trx()
    {

        $refcode = "ENK" . random_int(10, 99) . date('YmdHis');


        return $refcode;
    }
}


if (!function_exists('get_user_token')) {

    function get_user_token($phone_no, $password)
    {

        $databody = array(
            'phone' => $phone_no,
            'password' => $password,
        );

        $body = json_encode($databody);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://enkpayapp.enkwave.com/api/phone-login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
            ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);

        return  $var->data->token;




    }
}


if (!function_exists('store_vfd_banks')) {
    function store_vfd_banks()
    {

        $errand_key = ErrandKey::where('id', 1)->first()->errand_key ?? null;


        if ($errand_key == null) {
            $response1 = errand_api_key();
            $update = ErrandKey::where('id', 1)
                ->update([
                    'errand_key' => $response1[0],
                ]);
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.errandpay.com/epagentservice/api/v1/ApiGetBanks',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $errand_key",
            ),
        ));

        $var = curl_exec($curl);

        curl_close($curl);
        $var = json_decode($var);

        $result = $var->data ?? null;

        $status = $var->code ?? null;

        $chk_bank = VfdBank::select('*')->first()->bank_code ?? null;
        if ($chk_bank == null || empty($chk_bank)) {
            $history = [];
            foreach ($var->data as $key => $value) {
                $history[] = array(
                    "bank_name" => $value->bankName,
                    "code" => $value->code,
                );
            }

            DB::table('vfd_banks')->insert($history);
        }
    }
}


if (!function_exists('store_providus_banks')) {
    function store_providus_banks()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://154.113.16.142:8882/postingrest/GetNIPBanks',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',

        ));

        $var = curl_exec($curl);

        curl_close($curl);
        $var = json_decode($var);


        $result = $var->banks ?? null;

        $status = $var->code ?? null;

        $chk_bank = ProvidusBank::select('*')->first()->bank_code ?? null;
        if ($chk_bank == null || empty($chk_bank)) {
            $history = [];
            foreach ($var->banks as $key => $value) {
                $history[] = array(
                    "bank_name" => $value->bankName,
                    "code" => $value->bankCode,
                );
            }

            $rr = DB::table('providus_banks')->insert($history);

            return $rr;
        }
    }
}


if (!function_exists('get_banks')) {
    function get_banks()
    {

        $set = Setting::where('id', 1)->first();
        if ($set->bank == 'vfd') {
            $get_banks = VfdBank::select('bankName', 'code')->get();
            return $get_banks;
        }


        if ($set->bank == 'ttmfb') {
            $get_banks = Ttmfb::select('bankName', 'code')->get();
            return $get_banks;
        }


        // if($set->bank == 'manuel'){
        //     $get_banks = ProvidusBank::select('bankName', 'code')->get();
        //     return $get_banks;
        // }


        if ($set->bank == 'manuel') {
            $get_banks = VfdBank::select('bankName', 'code')->get();

            return $get_banks;
        }


        if ($set->bank == 'pbank') {
            $get_banks = ProvidusBank::select('bankName', 'code')->get();
            return $get_banks;
        }


        if ($set->bank == 'woven') {

            $api = env('WOVENKEY');
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.woven.finance/v2/api/banks',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    "api_secret: $api"
                ),
            ));

            $var = curl_exec($curl);

            curl_close($curl);
            $var = json_decode($var);

            $result = $var->banks ?? null;
            $status = $var->status ?? null;

            $get_wbanks = [];
            if ($status == "success") {
                foreach ($var->data as $key => $value) {
                    $get_wbanks[] = array(
                        "bankName" => $value->name,
                        "code" => $value->bank_code,
                    );
                }


                return $get_wbanks;

            }


        }


    }
}


if (!function_exists('resolve_bank')) {
    function resolve_bank($bank_code, $account_number)
    {

        $set = Setting::where('id', 1)->first();

        if ($set->bank == 'ttmfb') {


            $username = env('MUSERNAME');
            $prkey = env('MPRKEY');
            $sckey = env('MSCKEY');

            $unixTimeStamp = timestamp();
            $sha = sha512($unixTimeStamp . $prkey);
            $authHeader = 'magtipon ' . $username . ':' . base64_encode(hex2bin($sha));


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://magtipon.buildbankng.com/api/v1/bank/$bank_code/account/$account_number",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                //CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: $authHeader",
                    "Timestamp: $unixTimeStamp",
                    'Content-Type: application/json',
                ),
            ));

            $var = curl_exec($curl);
            curl_close($curl);
            $var = json_decode($var);


            $customer_name = $var->AccountName ?? null;
            $error = $var->error->message ?? null;

            $status = $var->ResponseCode ?? null;


            if ($status == 90000) {

                return $customer_name;
            }

            return $error;
        }

        if ($set->bank == 'manuel') {

            $databody = array(

                'accountNumber' => $account_number,
                'institutionCode' => $bank_code,
                'channel' => "Bank",

            );

            $body = json_encode($databody);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.errandpay.com/epagentservice/api/v1/AccountNameVerification',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            ));

            $var = curl_exec($curl);
            curl_close($curl);
            $var = json_decode($var);

            $customer_name = $var->data->name ?? null;
            $error = $var->error->message ?? null;

            $status = $var->code ?? null;

            if ($status == 200) {

                return $customer_name;
            }

            return $error;
        }

        if ($set->bank == 'pbank') {

            $databody = array(

                'accountNumber' => $account_number,
                'institutionCode' => $bank_code,
                'channel' => "Bank",

            );

            $body = json_encode($databody);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.errandpay.com/epagentservice/api/v1/AccountNameVerification',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            ));

            $var = curl_exec($curl);
            curl_close($curl);
            $var = json_decode($var);

            $customer_name = $var->data->name ?? null;
            $error = $var->error->message ?? null;

            $status = $var->code ?? null;

            if ($status == 200) {

                return $customer_name;
            }

            return $error;
        }


        if ($set->bank == 'vfd') {


            $customer_name = AccountInfo::where('account_no', $account_number)
                ->where('code', $bank_code)->first()->customer_name ?? null;

            if ($customer_name != null) {
                return $customer_name;
            }


            if (!empty($customer_name) || $customer_name == null) {

                $databody = array(

                    'accountNumber' => $account_number,
                    'institutionCode' => $bank_code,
                    'channel' => "Bank",

                );

                $body = json_encode($databody);
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.errandpay.com/epagentservice/api/v1/AccountNameVerification',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $body,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                    ),
                ));

                $var = curl_exec($curl);
                curl_close($curl);
                $var = json_decode($var);

                $customer_name = $var->data->name ?? null;
                $error = $var->error->message ?? null;

                $status = $var->code ?? null;

                $bankName = VfdBank::where('code', $bank_code)->first()->bankName;

                if ($status == 200) {

                    $sv = new AccountInfo();
                    $sv->account_no = $account_number;
                    $sv->code = $bank_code;
                    $sv->bankName = $bankName;
                    $sv->customer_name = $customer_name;
                    $sv->save();

                    return $customer_name;
                }

                return $error;
            }
        }


        if ($set->bank == 'woven') {


            $api = env('WOVENKEY');

            $databody = array(
                'account_number' => $account_number,
                'bank_code' => $bank_code,
            );

            $body = json_encode($databody);
            $curl = curl_init();


            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.woven.finance/v2/api/nuban/enquiry",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    "api_secret: $api",
                    'Content-Type: application/json',
                ),
            ));

            $var = curl_exec($curl);
            curl_close($curl);
            $var = json_decode($var);

            $error = $var->message ?? null;
            $status = $var->status ?? null;
            $customer_name = $var->data->account_name ?? null;
            if ($status == "success") {
                return $customer_name;
            }

            return $error;
        }


    }
}


if (!function_exists('create_v_account')) {
    function create_v_account($user_id)
    {

        $errand_key = errand_api_key();
        $errand_user_id = errand_id();
        $bvn = user_bvn() ?? null;

        $user_id = User::where('bvn', $bvn)->first()->id ?? null;


        $chk_V_account = VirtualAccount::where('user_id', $user_id)->where('v_bank_name', 'VFD MFB')->first() ?? null;
        if (empty($chk_V_account) || $chk_V_account == null) {
            if (Auth::user()->b_name == null) {
                $name = first_name() . " " . last_name();
            } else {
                $name = Auth::user()->b_name;
            }

            if (Auth::user()->b_phone == null) {
                $phone = Auth::user()->phone;
            } else {
                $phone = Auth::user()->b_phone;
            }

            if (user_status() == 0) {

                return response()->json([
                    'status' => false,
                    'message' => 'User has been restricted on ENKPAY',
                ], 500);
            }

            if (user_status() == 1) {

                return response()->json([
                    'status' => false,
                    'message' => 'Please complete your KYC',
                ], 500);
            }

            if (user_bvn() == null) {

                return response()->json([
                    'status' => false,
                    'message' => 'We need your BVN to generate an account for you',
                ], 500);
            }

            if (Auth::user()->v_account_number !== null) {

                return response()->json([
                    'status' => false,
                    'message' => 'You already own account number',
                ], 500);
            }

            if ($bvn == null) {

                return response()->json([
                    'status' => false,
                    'message' => 'BVN not verified, Kindly update your BVN',
                ], 500);
            }

            $curl = curl_init();
            $data = array(

                "userId" => $errand_user_id,
                "customerBvn" => $bvn,
                "phoneNumber" => $phone,
                "customerName" => $name,

            );

            $databody = json_encode($data);

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.errandpay.com/epagentservice/api/v1/CreateVirtualAccount',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $databody,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    "Authorization: Bearer $errand_key",
                ),
            ));

            $var = curl_exec($curl);


            curl_close($curl);
            $var = json_decode($var);


            $status = $var->code ?? null;
            $acct_no = $var->data->accountNumber ?? null;
            $acct_name = $var->data->accountName ?? null;
            $error = $var->error->message ?? null;

            $bank = "VFD MFB";

            if ($status == 200) {

                $create = new VirtualAccount();
                $create->v_account_no = $acct_no;
                $create->v_account_name = $acct_name;
                $create->v_bank_name = $bank;
                $create->user_id = Auth::id();
                $create->save();

                $user = User::find(Auth::id());
                $user->v_account_no = $acct_no;
                $user->v_account_name = $acct_name;
                $user->save();

                $get_user = User::find(Auth::id())->first();

                $message = "VFD Account Created | $name";
                send_notification($message);
                return 200;
            } else {
                $message = "VFD ERROR | $name | " . $error ?? null;
                send_notification($message);
                return 500;
            }
        }
    }
}

function create_p_account()
{
    if (!function_exists('create_p_account')) {

        $bvn = user_bvn() ?? null;
        $user_id = User::where('bvn', $bvn)->first()->id ?? null;


        dd('hellop');


        $client = env('CLIENTID');
        $xauth = env('HASHKEY');

        $user_id = User::where('bvn', $bvn)->first()->id ?? null;

        if (Auth::user()->b_name == null) {
            $name = first_name() . " " . last_name();
        } else {
            $name = Auth::user()->b_name;
        }

        if (Auth::user()->b_phone == null) {
            $phone = Auth::user()->phone;
        } else {
            $phone = Auth::user()->b_phone;
        }

        $curl = curl_init();
        $data = array(
            "account_name" => $name,
            "bvn" => $bvn,
        );


        $databody = json_encode($data);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://vps.providusbank.com/vps/api/PiPCreateReservedAccountNumber',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $databody,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                "Client-Id: $client",
                "X-Auth-Signature: $xauth",
            ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);

        dd($var);

        $status = $var->responseCode ?? null;
        $p_acct_no = $var->account_number ?? null;
        $p_acct_name = $var->account_name ?? null;
        $error = $var->responseMessage ?? null;

        $pbank = "PROVIDUS BANK";

        if ($status == 00) {

            $create = new VirtualAccount();
            $create->v_account_no = $p_acct_no;
            $create->v_account_name = $p_acct_name;
            $create->v_bank_name = $pbank;
            $create->user_id = Auth::id();
            $create->save();

            $message = "Providus Account Created | $name";
            send_notification($message);

            $get_user = User::find(Auth::id())->first();

            return 200;
        } else {


            $error = "Providus account Error | $name | $error";
            send_notification($error);

            return 400;
        }
    }
}


if (!function_exists('decryption')) {

    function decryption($encryptedStr)
    {
        $s_key = env('SKEY');
        $INV_key = env('INVKEY');

        $key = base64_decode($s_key);
        $iv = base64_decode($INV_key);
        $cipherText = base64_decode($encryptedStr);
        $decryptedText = openssl_decrypt($cipherText, 'AES-256-CFB', $key, OPENSSL_RAW_DATA, $iv);
        return $decryptedText;
    }
}


if (!function_exists('encrypt')) {

    function encrypt($strToEncrypt)
    {


        $s_key = env('SKEY');
        $INV_key = env('INVKEY');

        $key = base64_decode($s_key);
        $iv = base64_decode($INV_key);
        $cipherText = openssl_encrypt($strToEncrypt, 'AES-256-CFB', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($cipherText);
    }
}


function guid()
{
    function s4()
    {
        return substr(md5(uniqid(rand(), true)), 0, 4);
    }

    return s4() . s4() . s4() . s4() . s4() . s4() . s4() . s4();
}

function timestamp()
{
    return substr(strval(time()), 0, 10);
}

function sha512($message)
{
    return hash('sha512', $message);
}


if (!function_exists('get_pool')) {

    function get_pool()
    {

        try {

            $api = errand_api_key();
            $epKey = env('EPKEY');

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.errandpay.com/epagentservice/api/v1/ApiGetBalance',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Content-Type: application/json',
                    "epKey: $epKey",
                    "Authorization: Bearer $api",
                ),
            ));

            $var = curl_exec($curl);


            curl_close($curl);

            $var = json_decode($var);


            $code = $var->code ?? null;

            if ($code == null) {

                return "Network Issue";
            }

            if ($var->code == 200) {
                return $var->data->balance;
            }
        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }
}


function ttmfb_balance()
{


    $username = env('MUSERNAME');
    $prkey = env('MPRKEY');
    $sckey = env('MSCKEY');

    $unixTimeStamp = timestamp();
    $sha = sha512($unixTimeStamp . $prkey);
    $authHeader = 'magtipon ' . $username . ':' . base64_encode(hex2bin($sha));


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://magtipon.buildbankng.com/api/v1/account/balance",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        //CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => array(
            "Authorization: $authHeader",
            "Timestamp: $unixTimeStamp",
            'Content-Type: application/json',
        ),
    ));

    $var = curl_exec($curl);
    curl_close($curl);
    $var = json_decode($var);


    $balance = $var->Balance ?? null;
    $error = $var->error->message ?? null;

    $status = $var->ResponseCode ?? null;

    if ($status == 90000) {

        return $balance;
    } else {

        return "No Network";
    }
}

function woevn_balance()
{

    $account = Setting::where('id', 1)->first()->r_account;
    $api = env('WOVENKEY');

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.woven.finance/v2/api/reserved_vnuban/$account",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        //CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => array(
            "api_secret: $api",
            'Content-Type: application/json',
        ),
    ));

    $var = curl_exec($curl);
    curl_close($curl);
    $var = json_decode($var);

    $balance = $var->Balance ?? null;
    $error = $var->error->message ?? null;

    $status = $var->status ?? null;

    if ($status == "success") {

        return $var->data->available_balance;


    } else {
        return "No Network";
    }
}


function psb_data()
{


    $psb_phone = env('PSBPHONE');
    $psb_pass = env('PSBPASS');


    $databody = array(

        "phone" => $psb_phone,
        "password" => $psb_pass

    );

    $post_data = json_encode($databody);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://etopagency.com/api/agent/phone-login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $post_data,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
        ),
    ));

    $var = curl_exec($curl);
    curl_close($curl);
    $var = json_decode($var);

    $status = $var->status ?? null;
    if ($status == true) {

        $data['balance'] = $var->data->main_wallet;
        $data['token'] = $var->data->token;
        return $data;
    }


}


if (!function_exists('credit_user_wallet')) {
    function credit_user_wallet($url, $user_email, $amount, $order_id, $type, $session_id)
    {

        try {

            $curl = curl_init();
            $data = array(
                'session_id' => $session_id,
            );
            $post_data = json_encode($data);

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://etopagency.com/api/update-session',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => $post_data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $var = curl_exec($curl);
            curl_close($curl);
            $var = json_decode($var);


        } catch (\Exception $th) {
            return $th->getMessage();
        }

        $databody = array(
            "amount" => $amount,
            "email" => $user_email,
            "order_id" => $order_id,
        );

        $post_data = json_encode($databody);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);
        $status = $var->status ?? null;


        if ($status == true) {
            if ($type == "wresolve") {
                $date = date('dmy h:i:s');
                $message = "Wema Resolve ======> $user_email has been funded NGN$amount \n| 0n $url \n using reslove | on $date";
                send_notification_resolve($message);
            } elseif ($type == "presolve") {

                $date = date('dmy h:i:s');
                $message = "9psb Resolve ======> $user_email has been funded NGN$amount \n| 0n $url \n using reslove on $date";
                send_notification_resolve($message);

            } elseif ($type == "woresolve") {

                $date = date('dmy h:i:s');
                $message = "Woven Resolve ======> $user_email has been funded NGN$amount \n| 0n $url \n using reslove on $date";
                send_notification_resolve($message);

            } else {

                $message = "$url  | $user_email | $amount | $order_id successfully funded";
                send_notification($message);

            }

            return 2;

        } else {

            if ($type == "wresolve") {
                $message = "Error Reslove Wema ======>  $url | $user_email | $amount | $order_id" .
                    "\n\n Funding user Error ===>" . json_encode($var);
                send_notification_resolve($message);
            } elseif ($type == "presolve") {
                $message = "Error Reslove PSB ======>  $url | $user_email | $amount | $order_id" .
                    "\n\n Funding user Error ===>" . json_encode($var);
                send_notification_resolve($message);

            } else {

                $message = "Error Reslove WOVEN ======>  $url | $user_email | $amount | $order_id" .
                    "\n\n Funding user Error ===>" . json_encode($var);
                send_notification_resolve($message);

            }

            $message = "request ======>  $url | $user_email | $amount | $order_id" .
                "\n\n Funding user Error ===>" . json_encode($var);
            send_notification($message);
            return 0;
        }

    }

}


function woven_create($first_name, $last_name, $bvn, $nin)
{


    $set = Setting::where('id', 1)->first();

    $key = env('WOVENKEY');
    $databody = array(
        "customer_reference" => $last_name . "_" . $first_name . random_int(0000, 9999),
        "name" => $last_name . " " . $first_name,
        "email" => Auth::user()->email,
        "mobile_number" => Auth::user()->phone,
        "bvn" => $bvn,
        "nin" => $nin,//98399716687,
        "callback_url" => url('') . "/api/transfer-webhook",
        "collection_bank" => $set->woven_collective_code,
    );

    $post_data = json_encode($databody);
    $curl = curl_init();


    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.woven.finance/v2/api/vnubans/create_customer',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $post_data,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "api_secret: $key"
        ),
    ));
    $var = curl_exec($curl);
    curl_close($curl);
    $var = json_decode($var);
    $status = $var->status ?? null;


    if ($status = "success") {
        $data['account_no'] = $var->data->vnuban;
        $data['bank_name'] = $var->data->bank_name;
        $data['account_name'] = $var->data->account_name;
        $data['status'] = 00;
        return $data;
    } else {
        $data['status'] = 99;
        $data['error'] = "Wov Error ===>>> " . $var->message;
        return $data;
    }


}

