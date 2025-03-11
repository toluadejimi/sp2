@extends('layouts.app')
@section('content')



<div class="boarding-section">
    <div class="tf-container">
        <div class="images">
            <img style="height: 100px; width: 100px" src="{{url('')}}/public/assets/images/boarding/pending.png" alt="image" >
        </div>
    </div>
</div>

<div class="boarding-content mt-7">
    <div class="tf-container">
        <div class="boarding-title">
            @if($message == 1)

                <h1 class="tf-title">Email Resent!</h1>
                <p>A verification Email has been sent to your registered email</p>

            @else
                <h1 class="tf-title">Account Pending!</h1>
                <p>A verification Email has been sent to your registered email</p>
                <p>Your submission has been successful, We will inform you when your account is approved</p>
            @endif

        </div>
        <a href="/get-started" class="tf-btn accent large">Get Started</a>

        @if($email != null)
            <a href="resend-email?email={{$email}}" class="tf-btn danger large my-2">Resend Email</a>
        @else
        @endif

    </div>
</div>


@endsection
