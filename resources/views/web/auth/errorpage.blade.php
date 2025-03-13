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

                <h1 class="tf-title text-danger">ERROR {{$error_code}}</h1>
                <p>{{$error_message}}</p>

            @else
                <h1 class="tf-title">ERROR !!</h1>
            @endif

        </div>
        <a href="/get-started" class="tf-btn accent large">Get Started</a>



    </div>
</div>


@endsection
