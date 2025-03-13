<!DOCTYPE html>
@extends('layouts.app')
@section('content')

<div class="mt-7 login-section">
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


            <div class="mb-9 fw-3 text-center">

                <h1>Set your Password</h1>
                <p class="my-4">Choose a strong and password you can remember</p>
            </div>



        <form class="tf-form" action="set_password_now" id="login-form" method="post">
            @csrf

            <div class="group-input mt-2">
                <label>Password</label>
                <input type="text" name="password"  placeholder="*********" required>
                <input type="email" name="email"   value="{{$email}}" hidden>
                <input type="text" name="code" hidden value="{{$code}}">
            </div>


            <div class="group-input mt-2">
                <label>Confirm Password</label>
                <input type="text" name="password_confirm"  placeholder="*********" required>
            </div>



            <button type="submit" class="tf-btn accent" id="submit-btn">
                <span id="btn-text">Set New Password</span>
                <span id="btn-loader" class="loader" style="display: none;"></span>
            </button>


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
                    let form = document.getElementById("login-form");

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



        </form>
        <div class="auth-line">Or</div>
{{--        <ul class="bottom socials-login mb-4">--}}
{{--            <li><a href="/dashboard"><img src="{{url('')}}/public/assets/images/icon-socials/facebook.png" alt="image">Continue with Facebook</a></li>--}}
{{--            <li><a href="/dashboard"><img src="{{url('')}}/public/assets/images/icon-socials/google.png" alt="image">Continue with Google</a></li>--}}
{{--            <li><a href="/dashboard"><img src="{{url('')}}/public/assets/images/icon-socials/apple.png" alt="image">Continue with Google</a></li>--}}
{{--        </ul>--}}
        <p class="mb-9 fw-3 text-center ">Remember your login now? <a href="/get_started" class="auth-link-rg" >Login</a></p>
    </div>
</div>



@endsection
