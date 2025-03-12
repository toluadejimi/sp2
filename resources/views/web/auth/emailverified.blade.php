@extends('layouts.app')
@section('content')



<div class="boarding-section">
    <div class="tf-container">
        <div class="images">
            <img style="height: 100px; width: 100px" src="{{url('')}}/public/assets/images/boarding/mail.png" alt="image" >
        </div>
    </div>
</div>

<div class="boarding-content mt-7">
    <div class="tf-container">
        <div class="boarding-title">
            @if($message == 0)

                <h1 class="tf-title">Email Verified</h1>
                <p>Your email has been verified</p>



            @else
                <h1 class="tf-title">Invalid Email</h1>
                <p>User Could not be found</p>
            @endif

        </div>

        <a href="/get-started" class="tf-btn accent large">Login</a>




    </div>
</div>


@endsection
