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

        <form class="tf-form" action="register_now" id="register-form" method="post">
            @csrf
            <h1>Register</h1>


            <div class="auth-line">Business Information</div>


            <div class="group-input">
                <label>Business Name</label>
                <input type="text" name="b_name" placeholder="Sprint Pay Inc" required>
            </div>


            <div class="group-input">
                <label>Website Url</label>
                <input type="text" name="site_url" placeholder="https://sprintpay.com" required>
            </div>

            <div class="auth-line">Personal Information</div>


            <div class="group-input">
                <label>First Name</label>
                <input type="text" name="first_name" placeholder="John" required>
            </div>

            <div class="group-input">
                <label>Last Name</label>
                <input type="text" name="last_name" placeholder="Doe" required>
            </div>

            <div class="group-input">
                <label>Email</label>
                <input type="email" name="email" placeholder="info@example.com" required>
            </div>

            <div class="group-input">
                <label>Phone</label>
                <input type="text" name="phone" value="+234"  required>
            </div>

            <div class="group-input auth-pass-input last">
                <label>Password</label>
                <input type="password" name="password" class="password-input" placeholder="Password" required>
                <a class="icon-eye password-addon" id="password-addon"></a>
            </div>



            <button type="submit" class="tf-btn accent" id="submit-btn">
                <span id="btn-text">Register</span>
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
                    let form = document.getElementById("register-form");

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
        <p class="mb-9 fw-3 text-center ">Already had an account? <a href="/login" class="auth-link-rg" >Login</a></p>
    </div>
</div>



@endsection
