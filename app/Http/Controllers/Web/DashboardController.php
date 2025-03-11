<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(request $request)
    {

        $data['transactions'] = Transaction::latest()->where('user_id', Auth::id())->take('50')->get();
        return view('web.user.dashboard', $data);

    }

    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            $user->session_id = null;
            $user->device_details = null;
            $user->save();
        }

        Auth::logout();
        return redirect()->route('login');
    }
}
