<?php

namespace App\Http\Controllers\Auth;

use App\Models\OauthAccessToken;
use App\Models\VirtualAccount;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Feature;
use App\Models\Setting;



use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use Laravel\Passport\Passport;
use Laravel\Passport\HasApiTokens;

use function PHPUnit\Framework\isEmpty;

class LoginController extends Controller
{

    protected $deviceService;

    // Inject the DeviceService into the controller
    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    public function login(request $request)
    {


        $phone = preg_replace('/^\[?0\]?/', '', $request->phone);
        $phone_no = ['phone' => "+234".$phone, 'password' => $request->password];

        if (Auth::attempt($phone_no)) {
            $user = Auth::user();

            $deviceDetails = $this->deviceService->getDeviceDetails();
            $user->session_id = Str::random(60);
            $user->device_details = $deviceDetails;
            $user->save();

            session(['session_id' => $user->session_id, 'device_details' => $user->device_details]);

            return redirect('dashboard');
        }

        return back()->with('error', "Email or Password Incorrect");





    }


    public function register_now(request $request)
    {


        $chk_user_email = User::where('email', $request->email)->first() ?? null;
        $chk_user_no = User::where('phone', $request->phone)->first() ?? null;
        $chk_nin = User::where('phone', $request->phone)->first()->identification_number ?? null;
        $chk_bvn = User::where('phone', $request->phone)->first()->bvn ?? null;


        if($chk_user_email != null){

            $status = User::where('email', $request->email)->first()->status;
            if($status == 0){
                return view('web.auth.pending_verification');
            }
            return back()->with('error', "User with the email already exist");
        }

        $code = random_int(000000, 999999);
        $url = url('')."/email-verification?email=$request->email&code=$code";




        if($chk_user_no != null){

            $status = User::where('phone', $request->phone)->first()->status;
            if($status == 0){
                $data['email'] = $request->email;
                $data['code'] = $code;
                return view('web.auth.pending_verification', $data);
            }
            return back()->with('error', "User with phone no already exist");
        }



        $data = array(
            'fromsender' => env('MAIL_USERNAME'), 'EnkPay',
            'subject' => "Verify Your Account",
            'toreceiver' => $request->email,
            'user' => $request->first_name,
            'url' => $url,
        );

       $send_mail = Mail::send('emails.verifymail', ["data1" => $data], function ($message) use ($data) {
            $message->from($data['fromsender']);
            $message->to($data['toreceiver']);
            $message->subject($data['subject']);
        });


       if($send_mail){
           $reg = new User();
           $reg->first_name = $request->first_name;
           $reg->last_name = $request->last_name;
           $reg->phone = $request->phone;
           $reg->email = $request->email;
           $reg->bvn = $request->bvn;
           $reg->sms_code = $code;
           $reg->identification_number = $request->nin;
           $reg->save();
       }


        if($reg){

            $data['email'] = $request->email;
            $data['code'] = $request->code;
            return view('web.auth.pending_verification', $data);
        }
        return back()->with('error', 'Something went wrong');


    }


    public function resend_email(request $request)
    {



        $code = User::where('email', $request->email)->first()->sms_code;
        $url = url('')."/email-verification?email=$request->email&code=$code";


        $data = array(
            'fromsender' => env('MAIL_USERNAME'), 'EnkPay',
            'subject' => "Verify Your Account",
            'toreceiver' => $request->email,
            'user' => $request->first_name,
            'url' => $url,
        );

        $send_mail = Mail::send('emails.verifymail', ["data1" => $data], function ($message) use ($data) {
            $message->from($data['fromsender']);
            $message->to($data['toreceiver']);
            $message->subject($data['subject']);
        });


        if($send_mail){
            $data['message'] = 1;
            return view('web.auth.pending_verification', $data);
        }



    }



}
