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

        <form class="tf-form" action="register_now" method="post">
            @csrf
            <h1>Register</h1>


            <div class="auth-line">Business Information</div>


            <div class="group-input">
                <label>Business Name</label>
                <input type="text" name="b_name" placeholder="Sprint Pay Inc">
            </div>


            <div class="group-input">
                <label>Website Url</label>
                <input type="text" name="site_url" placeholder="https://sprintpay.com" >
            </div>

            <div class="auth-line">Personal Information</div>


            <div class="group-input">
                <label>First Name</label>
                <input type="text" name="first_name" placeholder="John">
            </div>

            <div class="group-input">
                <label>Last Name</label>
                <input type="text" name="last_name" placeholder="Doe">
            </div>

            <div class="group-input">
                <label>Email</label>
                <input type="email" name="email" placeholder="info@example.com">
            </div>

            <div class="group-input">
                <label>Phone</label>
                <input type="text" name="phone" value="+234" >
            </div>

            <div class="group-input auth-pass-input last">
                <label>Password</label>
                <input type="password" name="password" class="password-input" placeholder="Password">
                <a class="icon-eye password-addon" id="password-addon"></a>
            </div>




            <button type="submit" class="tf-btn accent large">Register</button>

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
