<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Transactions;
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


    public function all_history(request $request)
    {

        $data['transactions'] = Transaction::latest()->where('user_id', Auth::id())
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->take('100')->get();

        $data['all_income'] = Transaction::where('user_id', Auth::id())->where('status', 1)->sum('credit');
        $data['all_outcome'] = Transaction::where('user_id', Auth::id())->where('status', 1)->sum('debit');
        return view('web.history.index', $data);

    }

    public function filter_transaction(request $request)
    {


        if ($request->from != null && $request->to != null) {

            $data['transactions'] = Transaction::whereBetween('created_at', [$request->from . ' 00:00:00', $request->to . ' 23:59:59'])
                ->where('user_id', Auth::id())
                ->take(1000)->get();

            $data['all_income'] = Transaction::whereBetween('created_at', [$request->from . ' 00:00:00', $request->to . ' 23:59:59'])->where('user_id', Auth::id())->where('status', 1)->sum('credit');
            $data['all_outcome'] = Transaction::whereBetween('created_at', [$request->from . ' 00:00:00', $request->to . ' 23:59:59'])->where('user_id', Auth::id())->where('status', 1)->sum('debit');

            return view('web.history.index', $data);
        }


        if ($request->from != null) {


            $data['transactions'] = Transaction::whereDate('created_at', $request->from)
                ->where('user_id', Auth::id())
                ->take(1000)
                ->get();


            $data['all_income'] = Transaction::whereDate('created_at', $request->from)->where([
                'user_id' => Auth::id(),
                'status' => 1,
            ])->sum('credit');

            $data['all_outcome'] = Transaction::whereDate('created_at', $request->from)->where([
                'user_id' => Auth::id(),
                'status' => 1,
            ])->sum('debit');

            return view('web.history.index', $data);
        }


        if ($request->status != null) {
            $data['transactions'] = Transaction::where('status', $request->status)
                ->where('user_id', Auth::id())
                ->get();

            $data['all_income'] = Transaction::where([
                'user_id' => Auth::id(),
                'status' => $request->status,
            ])->sum('credit');

            $data['all_outcome'] = Transaction::where([
                'user_id' => Auth::id(),
                'created_at' => $request->from,
                'status' => $request->status,

            ])->sum('debit');

            return view('web.history.index', $data);
        }

        if ($request->email != null) {
            $data['transactions'] = Transaction::where('email', $request->email)
                ->where('user_id', Auth::id())
                ->get();

            $data['all_income'] = Transaction::where([
                'user_id' => Auth::id(),
                'email' => $request->email,
            ])->sum('credit');

            $data['all_outcome'] = Transaction::where([
                'user_id' => Auth::id(),
                'created_at' => $request->from,
                'email' => $request->email,

            ])->sum('debit');

            return view('web.history.index', $data);
        }

        if ($request->from != null &&  $request->status != null) {
            $data['transactions'] = Transaction::where([
                'user_id' => Auth::id(),
                'created_at' => $request->from,
                'status' => $request->status,
            ])->get();

            $data['all_income'] = Transaction::where([
                'user_id' => Auth::id(),
                'created_at' => $request->from,
                'status' => $request->status,
            ])->sum('credit');

            $data['all_outcome'] = Transaction::where([
                'user_id' => Auth::id(),
                'created_at' => $request->from,
                'status' => $request->status,

            ])->sum('debit');

            return view('web.history.index', $data);
        }


    }


}
