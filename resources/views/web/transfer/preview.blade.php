@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.app')
@section('content')

    <div class="app-header st1">
        <div class="tf-container">
            <div class="tf-topbar d-flex justify-content-center align-items-center">
                <a href="#" class="back-btn"> <i class="icon-left white_color"></i></a>
                <h3 class="white_color">Transfer Preview</h3>
            </div>
            <h4 class="text-center white_color fw_4 mt-5">Transfer amount</h4>
            <h1 class="text-center white_color mt-2">₦{{number_format($data->amount, 2)}}</h1>
        </div>
    </div>
    <div class="card-secton transfer-section">
        <div class="tf-container">

            <div class="tf-balance-box transfer-confirm">
                <div class="inner-top">
                    <p>From</p>
                    <div class="tf-card-block d-flex align-items-center">
                        <div class="logo-img">
                            <i style="font-size: 30px" class="icon-wallet-filled-money-tool"></i>
                        </div>

                        @if($data->funds_account == "main_account")
                            <div class="info">
                                <h4><a href="#">Main Wallet</a></h4>
                                <p>₦{{number_format(Auth::user()->main_wallet, 2)}}</p>
                            </div>
                        @else
                            <div class="info">
                                <h4><a href="#">Bonus Wallet</a></h4>
                                <p>₦{{number_format(Auth::user()->bonus_wallet, 2)}}</p>
                            </div>
                        @endif

                    </div>
                </div>
                <div class="line"></div>
                <div class="inner-bottom">
                    <p>To</p>
                    <div class="tf-card-block d-flex align-items-center">

                        <div class="logo-img">
                            <i style="font-size: 30px" class="icon icon-bank2"></i>
                        </div>

                        <div class="info">
                            <h4><a href="#">{{$data->acct_name}}</a></h4>
                            <p>{{$data->acct_no}} </p>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
    <div class="transfer-list mt-5">
        <div class="tf-container">
            <ul class="list-view">
                <li>
                    Transaction fee
                    <span>₦25.00</span>
                </li>
                <li>
                    Total Amount
                    @php
                        $total = $data->amount + 25;
                    @endphp
                    <span>₦{{number_format($total, 2)}}</span>
                </li>
                <li>
                    Transfer form
                    @if($data->funds_account == "main_account")
                        <span>Main wallet</span>
                    @else
                        <span>Bonus wallet</span>

                    @endif
                </li>
                <li>
                    Narration
                    <span>{{$data->narration}}</span>
                </li>

            </ul>




        </div>
    </div>
    <div class="bottom-navigation-bar st1 bottom-btn-fixed">
        <div class="tf-container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif

                @php

                 if($total > Auth::user()->main_wallet){
                    $process = 0;
                  }else{
                     $process = 1;
                   }

                @endphp


                @if($data->funds_account == "main_account")

                    @if($process == 0)
                        <a href="dashboard" id="" class="tf-btn secondary large">Fund Account</a>
                    @else
                        <a href="#" id="btn-popup-down" class="tf-btn accent large">Continue</a>

                    @endif

                @else


                    @if($total < Auth::user()->bonus_wallet)
                        <a href="#" id="btn-popup-down" class="tf-btn secondary large">Fund Account</a>
                    @endif

                @endif




        </div>
    </div>
    <div class="tf-panel down">
        <div class="panel_overlay"></div>
        <div class="panel-box panel-down">
            <div class="header">
                <div class="tf-container">
                    <div class="tf-statusbar d-flex justify-content-center align-items-center">
                        <a href="#" class="clear-panel"> <i class="icon-close1"></i> </a>
                        <h3>Enter your PIN</h3>
                    </div>

                </div>
            </div>

            <div class="mt-5">
                <div class="tf-container">

                    <form class="tf-form-verify" method="post" action="transfer_now?id={{ $data->id }}" id="transfer-form">
                        @csrf
                        <div class="d-flex group-input-verify">
                            <input type="password" name="pin1" maxlength="1" pattern="[0-9]" class="input-verify" required>
                            <input type="password" name="pin2" maxlength="1" pattern="[0-9]" class="input-verify" required>
                            <input type="password" name="pin3" maxlength="1" pattern="[0-9]" class="input-verify" required>
                            <input type="password" name="pin4" maxlength="1" pattern="[0-9]" class="input-verify" required>
                            <input type="hidden" name="trx_id" value="{{ $data->id }}">
                        </div>

                        <div class="text-send-code">
                            <p class="fw_4"><a href="forgot_pin">Forgot Pin?</a></p>
                        </div>

                        @if($data->funds_account == "main_account")
                            @if($total > Auth::user()->main_wallet)
                                <div class="mt-7 mb-6">
                                    <button type="submit" class="tf-btn secondary">Fund your account</button>
                                </div>
                            @else
                                <div class="mt-7 mb-6">
                                    <button type="submit" class="tf-btn accent" id="submit-btn">
                                        <span id="btn-text">Continue</span>
                                        <span id="btn-loader" class="loader" style="display: none;"></span>
                                    </button>
                                </div>
                            @endif
                        @endif
                    </form>

                    <style>
                        .loader {
                            display: inline-block;
                            width: 15px;
                            height: 15px;
                            border: 2px solid #dae3ff;
                            border-radius: 50%;
                            border-top: 2px solid transparent;
                            animation: spin 0.5s linear infinite;
                            margin-left: 8px;
                        }

                        @keyframes spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                    </style>

                    <script>
                        document.getElementById("submit-btn").addEventListener("click", function(event) {
                            let form = document.getElementById("transfer-form");

                            if (!form.checkValidity()) {
                                form.reportValidity();
                                return;
                            }
                            event.preventDefault();

                            let btnText = document.getElementById("btn-text");
                            let btnLoader = document.getElementById("btn-loader");

                            btnText.style.display = "none";
                            btnLoader.style.display = "inline-block";
                            this.disabled = true;

                            setTimeout(() => form.submit(), 300);
                        });
                    </script>



                </div>


            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const inputs = document.querySelectorAll(".input-verify");

                    inputs.forEach((input, index) => {
                        input.addEventListener("input", function () {
                            if (this.value.length === 1 && index < inputs.length - 1) {
                                inputs[index + 1].focus(); // Move to next input
                            }
                        });

                        input.addEventListener("keydown", function (e) {
                            if (e.key === "Backspace" && index > 0 && this.value.length === 0) {
                                inputs[index - 1].focus(); // Move to previous input on Backspace
                            }
                        });
                    });
                });
            </script>


        </div>
    </div>

@endsection
