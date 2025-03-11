<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProxyController;
use App\Http\Controllers\Web\TransferController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/proxy', [ProxyController::class, 'proxy']);


Route::get('/',  [HomeController::class,'index']);
Route::get('get-started',  [HomeController::class,'get_started']);
Route::get('resend-email',  [LoginController::class,'resend_email']);



//Auth
Route::post('login_now',  [LoginController::class,'login']);
Route::post('register_now',  [LoginController::class,'register_now']);
Route::get('login',  [HomeController::class,'get_started'])->name('login');
Route::get('register',  [HomeController::class,'register'])->name('register');
Route::get('pending',  [HomeController::class,'pending'])->name('pending');


//Dashboard
Route::middleware(['checksession', 'single.login'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/logout', [DashboardController::class, 'logout']);
    Route::get('/profile', [ProfileController::class, 'index.blade.php']);
    Route::get('/bank-transfer', [TransferController::class, 'bank_transfer_index']);
    Route::post('/process_bank_transfer', [TransferController::class, 'process_bank_transfer']);
    Route::get('/transfer_preview', [TransferController::class, 'transfer_preview']);
    Route::post('/transfer_now', [TransferController::class, 'transfer_now']);
});

