@extends('layouts.app2')
@section('content')

    <div class="header is-fixed">
        <div class="tf-container">
            <div class="tf-statusbar d-flex justify-content-center align-items-center">
                <a href="#" class="back-btn"> <i class="icon-left"></i> </a>
                <h3>Transaction</h3>
            </div>
        </div>
    </div>

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


    <div id="app-wrap">
        <div class="bill-payment-content">
            <div class="tf-container">
                <div class="mt-3 bill-topbar">

                    <h4 class="fw_6">{{$trx->title}}</h4>
                </div>
                <div class="wrapper-bill">
                    <div class="archive-top">
                        @if($trx->transaction_type == "BankTransfer")
                            <span class="circle-box lg bg-white">
                                 <svg width="63" height="62" viewBox="0 0 299 299" fill="none"
                                      xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_5530_3480)">
                                    <mask id="mask0_5530_3480" style="mask-type:luminance" maskUnits="userSpaceOnUse"
                                          x="0" y="0" width="299" height="299">
                                    <path d="M0 0H299V299H0V0Z" fill="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M149.5 78.9362L119.623 97.7502H179.377L149.5 78.9362ZM152.571 53.6822C151.653 53.1041 150.59 52.7974 149.506 52.7974C148.421 52.7974 147.359 53.1041 146.441 53.6822L56.7985 110.136C51.9225 113.206 54.096 120.75 59.869 120.75H239.131C244.904 120.75 247.078 113.206 242.202 110.136L152.571 53.6822ZM51.75 230C51.75 226.95 52.9616 224.025 55.1183 221.868C57.2749 219.712 60.2 218.5 63.25 218.5H235.75C238.8 218.5 241.725 219.712 243.882 221.868C246.038 224.025 247.25 226.95 247.25 230C247.25 233.05 246.038 235.975 243.882 238.132C241.725 240.289 238.8 241.5 235.75 241.5H63.25C60.2 241.5 57.2749 240.289 55.1183 238.132C52.9616 235.975 51.75 233.05 51.75 230ZM80.5 132.25C83.55 132.25 86.4751 133.462 88.6317 135.618C90.7884 137.775 92 140.7 92 143.75V201.25C92 204.3 90.7884 207.225 88.6317 209.382C86.4751 211.539 83.55 212.75 80.5 212.75C77.45 212.75 74.5249 211.539 72.3683 209.382C70.2116 207.225 69 204.3 69 201.25V143.75C69 140.7 70.2116 137.775 72.3683 135.618C74.5249 133.462 77.45 132.25 80.5 132.25ZM115 132.25C118.05 132.25 120.975 133.462 123.132 135.618C125.288 137.775 126.5 140.7 126.5 143.75V201.25C126.5 204.3 125.288 207.225 123.132 209.382C120.975 211.539 118.05 212.75 115 212.75C111.95 212.75 109.025 211.539 106.868 209.382C104.712 207.225 103.5 204.3 103.5 201.25V143.75C103.5 140.7 104.712 137.775 106.868 135.618C109.025 133.462 111.95 132.25 115 132.25ZM149.5 132.25C152.55 132.25 155.475 133.462 157.632 135.618C159.788 137.775 161 140.7 161 143.75V201.25C161 204.3 159.788 207.225 157.632 209.382C155.475 211.539 152.55 212.75 149.5 212.75C146.45 212.75 143.525 211.539 141.368 209.382C139.212 207.225 138 204.3 138 201.25V143.75C138 140.7 139.212 137.775 141.368 135.618C143.525 133.462 146.45 132.25 149.5 132.25ZM184 132.25C187.05 132.25 189.975 133.462 192.132 135.618C194.288 137.775 195.5 140.7 195.5 143.75V201.25C195.5 204.3 194.288 207.225 192.132 209.382C189.975 211.539 187.05 212.75 184 212.75C180.95 212.75 178.025 211.539 175.868 209.382C173.712 207.225 172.5 204.3 172.5 201.25V143.75C172.5 140.7 173.712 137.775 175.868 135.618C178.025 133.462 180.95 132.25 184 132.25ZM218.5 132.25C221.55 132.25 224.475 133.462 226.632 135.618C228.788 137.775 230 140.7 230 143.75V201.25C230 204.3 228.788 207.225 226.632 209.382C224.475 211.539 221.55 212.75 218.5 212.75C215.45 212.75 212.525 211.539 210.368 209.382C208.212 207.225 207 204.3 207 201.25V143.75C207 140.7 208.212 137.775 210.368 135.618C212.525 133.462 215.45 132.25 218.5 132.25Z"
                                          fill="black"/>
                                    </mask>
                                    <g mask="url(#mask0_5530_3480)">
                                    <path
                                        d="M149.5 299C232.067 299 299 232.067 299 149.5C299 66.9334 232.067 0 149.5 0C66.9334 0 0 66.9334 0 149.5C0 232.067 66.9334 299 149.5 299Z"
                                        fill="#5481FF"/>
                                    </g>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_5530_3480">
                                    <rect width="299" height="299" fill="white"/>
                                    </clipPath>
                                    </defs>
                                    </svg>
                            </span>



                        @else

                            <span class="circle-box lg bg-critical">


                            <svg width="63" height="62" viewBox="0 0 63 62" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M31.5 11.2783L27.023 7.68753L22.5459 11.2824L18.069 7.68753V50.3189L22.5459 53.9139L27.023 50.3189L31.5 53.9139L31.6334 53.5819L32.3766 30.9701L31.6419 11.3564L31.5 11.2783Z"
                                    fill="white"/>
                                <path
                                    d="M40.454 11.2824L35.977 7.68753L31.5 11.2783V53.9139L35.977 50.3189L40.454 53.9139L44.931 50.3189V7.68753L40.454 11.2824Z"
                                    fill="white"/>
                                <path d="M21.681 17.808V21.364H31.642L31.9964 19.5859L31.642 17.808H21.681Z"
                                      fill="#C5C5C5"/>
                                <path d="M31.5051 17.808H35.6749V21.364H31.5051V17.808Z" fill="#C5C5C5"/>
                                <path d="M21.681 31.2109H29.7102V34.7669H21.681V31.2109Z" fill="#C5C5C5"/>
                                <path d="M21.681 38.3227H29.7102V41.8786H21.681V38.3227Z" fill="#4A84F6"/>
                                <path d="M21.6597 24.3728V27.9286H31.6419L31.9964 26.0385L31.6419 24.3728H21.6597Z"
                                      fill="#C5C5C5"/>
                                <path d="M31.5051 24.3728H41.3404V27.9287H31.5051V24.3728Z" fill="#C5C5C5"/>
                                <path
                                    d="M37.7163 40.5659C36.3815 40.4515 35.4027 39.943 34.7035 39.2438L35.6951 37.8327C36.1655 38.3285 36.8647 38.7734 37.7164 38.926V36.9555C36.407 36.6376 34.9832 36.1419 34.9832 34.413C34.9832 33.1291 36.0002 32.0358 37.7164 31.8578V30.6756H38.9114V31.8833C39.941 31.9977 40.8182 32.379 41.5047 33.0146L40.5005 34.3622C40.0429 33.9427 39.4835 33.6757 38.9114 33.5358V35.2901C40.2335 35.6206 41.7082 36.1292 41.7082 37.8707C41.7082 39.2819 40.7801 40.3751 38.9114 40.5658V41.7099H37.7164V40.5659H37.7163ZM37.7163 34.9979V33.4597C37.157 33.536 36.8391 33.841 36.8391 34.2733C36.8392 34.6419 37.1951 34.8326 37.7163 34.9979ZM38.9114 37.248V38.9514C39.5597 38.8242 39.8648 38.4556 39.8648 38.0488C39.8648 37.6294 39.4707 37.426 38.9114 37.248Z"
                                    fill="#F2C71C"/>
                            </svg>

                            </span>

                            @endif


                            </span>


                            @if($trx->credit == 0)
                                <h1><a href="#" class="critical_color">₦{{number_format($trx->debit, 2)}}</a></h1>
                            @else
                                <h1><a href="#" class="success_color">₦{{number_format($trx->credit, 2)}}</a></h1>
                            @endif


                            @if($trx->status == 1)
                                <h3 class="mt-2 fw_6">Transaction Successful</h3>
                            @elseif($trx->status == 0)
                                <h3 class="mt-2 fw_6">Transaction Pending</h3>
                            @elseif($trx->status == 3)
                                <h3 class="mt-2 fw_6">Transaction Reversed</h3>
                            @endif

                    </div>
                    <div class="dashed-line"></div>
                    <div class="archive-bottom">
                        <ul>
                            <li class="list-info-bill">Refrence <span>{{$trx->ref_trans_id}}</span></li>
                            <li class="list-info-bill">Date & Time <span>{{$trx->created_at}}</span></li>

                        </ul>

                        <hr>
                        @if($trx->transaction_type == "VirtualFundWallet")
                            <ul>
                                <li class="list-info-bill">Customer Email <span>{{$trx->email}}</span></li>
                            </ul>
                        @endif

                        @if($trx->transaction_type == "BankTransfer")
                            <ul>
                                <li class="list-info-bill">Beneficiary Name <span>{{$trx->receiver_name}}</span></li>
                                <li class="list-info-bill">Account No <span>{{$trx->receiver_account_no}}</span></li>
                                <li class="list-info-bill">Session ID <span>{{$trx->p_sessionId}}</span></li>


                            </ul>
                        @endif

                    </div>

                </div>


            </div>

        </div>
    </div>

    <div class="bottom-navigation-bar st1 bottom-btn-fixed">
        <div class="tf-container">
            <a href="#" class="tf-btn accent large">Share</a>
        </div>
    </div>

@endsection
