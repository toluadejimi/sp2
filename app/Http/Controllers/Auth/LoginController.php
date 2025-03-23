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
use Illuminate\Support\Facades\Config;
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

        $password = $request->password;


        $token = get_user_token($phone_no, $password);


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://enkpayapp.enkwave.com/api/user-info',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
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
        $message = $var->message ?? "Something went wrong";


        if (Auth::attempt($phone_no)) {


            if ($message === "Unauthenticated.") {
                $userId = Auth::user()->id();
                $usr = User::find($userId);
                if ($usr) {
                    $usr->token = $token;
                    $usr->save();
                }

            }


            $user = Auth::user();
            $deviceDetails = $this->deviceService->getDeviceDetails();
            $user->session_id = Str::random(60);
            $user->device_details = $deviceDetails;
            //$user->token = $token;
            $user->save();



            session(['session_id' => $user->session_id, 'device_details' => $user->device_details]);

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
                'fromsender' => 'noreply@enkpay.com', 'EnkPay',
                'subject' => "Login Notification",
                'ip' => $request->ip(),
                'date' => date('y-m-d h:i:s'),
                'user' => Auth::user()->first_name,
                'toreceiver' => Auth::user()->email,
            );

            Mail::send('emails.login', ["data1" => $data], function ($message) use ($data) {
                $message->from($data['fromsender']);
                $message->to($data['toreceiver']);
                $message->subject($data['subject']);
            });

            return redirect('dashboard');
        }



        return back()->with('error', "Email or Password Incorrect")->withInput();



    }


    public function register_now(request $request)
    {


        $chk_user_email = User::where('email', $request->email)->first() ?? null;
        $chk_user_no = User::where('phone', $request->phone)->first() ?? null;
        $chk_nin = User::where('phone', $request->phone)->first()->identification_number ?? null;
        $chk_bvn = User::where('phone', $request->phone)->first()->bvn ?? null;
        $code = random_int(000000, 999999);
        $url = url('')."/email-verification?email=$request->email&code=$code";





        if($chk_user_email != null){

            $status = User::where('email', $request->email)->first()->status;
            if($status == 0){

                $data['email'] = $request->email;
                $data['code'] = $code;
                $data['message'] = 0;

                return view('web.auth.pending_verification', $data);

            }
            return back()->with('error', "User with the email already exist");
        }



        if($chk_user_no != null){

            $status = User::where('phone', $request->phone)->first()->status;
            if($status == 0){
                $data['email'] = $request->email;
                $data['code'] = $code;
                $data['message'] = 0;

                return view('web.auth.pending_verification', $data);
            }
            return back()->with('error', "User with phone no already exist");
        }

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
           $reg->sms_code = $code;
           $reg->b_name = $request->b_name;
           $reg->password = bcrypt($request->password);
           $reg->save();
       }


        if($reg){

            $data['email'] = $request->email;
            $data['code'] = $request->code;
            $data['message'] = 0;
            return view('web.auth.pending_verification', $data);
        }
        return back()->with('error', 'Something went wrong');


    }


    public function resend_email(request $request)
    {



        $code = User::where('email', $request->email)->first()->sms_code;
        $url = url('')."/email-verification?email=$request->email&code=$code";

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
    public function email_verification(request $request)
    {


        $ck_user = User::where('email', $request->email)->first() ?? null;
        $ck_code = User::where('email', $request->email)->first()->sms_code ?? null;

        if($ck_user == null){
            $data['message'] = 1;
            return view('web.auth.emailverified', $data);
        }

        if($request->code == $ck_code){
            $data['message'] = 0;
            User::where('email', $request->email)->update(['is_email_verified' => 1]);
            return view('web.auth.emailverified', $data);
        }




    }
    public function reset_password(request $request)
    {
        return view('web.auth.reset-password');

    }

    public function reset_password_now(request $request)
    {

        $code = random_int(000000, 999999);
        $url = url('')."/set-password?email=$request->email&code=$code";
        $ck_user = User::where('email', $request->email)->first() ?? null;
        User::where('email', $request->email)->update(['sms_code' => $code]);


          if($ck_user){

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
                  'fromsender' => env('MAIL_USERNAME'), 'SprintPay',
                  'subject' => "Reset Password",
                  'toreceiver' => $request->email,
                  'user' => $request->first_name,
                  'url' => $url,
              );

              $send_mail = Mail::send('emails.resetpass', ["data1" => $data], function ($message) use ($data) {
                  $message->from($data['fromsender']);
                  $message->to($data['toreceiver']);
                  $message->subject($data['subject']);
              });

              if($send_mail){
                  return back()->with('message', 'An email has been sent to your registered email, Check spam if not available in inbox');
              }



          }else{
              return back()->with('error', "User Not found on our system");
          }

    }


    public function set_password(request $request)
    {

        $data['email'] = $request->email;
        $data['code'] = $request->code;

        if($data['email'] === null || $data['code'] === null){
            $data['error_code'] = "400";
            $data['message'] = 1;
            $data['error_message'] = "Parameters missing";
            return view('web.auth.errorpage', $data);
        }


        return view('web.auth.setpassword', $data);

    }

    public function set_password_now(request $request)
    {

        if($request->password != $request->password_confirm){
            return back()->with('error', 'Incorrect password');
        }

        $get_user_code = User::where('email', $request->email)->first()->sms_code ?? null;
        if($get_user_code == $request->code){
            $set_pass = User::where('email', $request->email)->update(['password' => bcrypt($request->password)]);
            if($set_pass){
                return redirect('get-started')->with('message', 'Password has been reset successfully, You can login now');
            }
        }else{

            $data['error_code'] = "400";
            $data['message'] = 1;
            $data['error_message'] = "Code does not match";

            return view('web.auth.errorpage', $data);

        }





    }





}
