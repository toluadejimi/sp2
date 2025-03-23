<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(request $request)
    {
        return view('web.auth.profile.index');

    }
}
