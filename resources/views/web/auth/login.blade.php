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

        <form class="tf-form" action="login_now" method="post">
            @csrf
            <h1>Login</h1>
            <div class="group-input">
                <label>Phone</label>
                <input type="number" name="phone" placeholder="0812345678">
            </div>
            <div class="group-input auth-pass-input last">
                <label>Password</label>
                <input type="password" name="password" class="password-input" placeholder="Password">
                <a class="icon-eye password-addon" id="password-addon"></a>
            </div>
            <a href="reset-password" class="auth-forgot-password mt-3">Forgot Password?</a>

            <button type="submit" class="tf-btn accent large">Log In</button>

        </form>
        <div class="auth-line">Or</div>
{{--        <ul class="bottom socials-login mb-4">--}}
{{--            <li><a href="home.html"><img src="{{url('')}}/public/assets/images/icon-socials/facebook.png" alt="image">Continue with Facebook</a></li>--}}
{{--            <li><a href="home.html"><img src="{{url('')}}/public/assets/images/icon-socials/google.png" alt="image">Continue with Google</a></li>--}}
{{--            <li><a href="home.html"><img src="{{url('')}}/public/assets/images/icon-socials/apple.png" alt="image">Continue with Google</a></li>--}}
{{--        </ul>--}}
        <p class="mb-9 fw-3 text-center ">Create a new account? <a href="/register" class="auth-link-rg" >Sign up</a></p>
    </div>
</div>



@endsection
