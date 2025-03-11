<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

class HomeController extends Controller
{

    public function index()
    {

        return view('web.welcome.index');

    }

    public function get_started()
    {
        return view('web.auth.login');
    }


    public function register()
    {
        return view('web.auth.register');
    }

    public function pending(request $request)
    {
        $data['email'] = null;
        $data['message'] = null;

        return view('web.auth.pending_verification', $data);
    }


}
