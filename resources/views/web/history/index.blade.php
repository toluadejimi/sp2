@extends('layouts.app2')
@section('content')


    <div class="header is-fixed">
        <div class="tf-container">
            <div class="tf-statusbar d-flex justify-content-center align-items-center">
                <a href="#" class="back-btn"> <i class="icon-left"></i> </a>
                <h3>History</h3>
                <a href="#" id="btn-popup-up" class="action-right"><i class="icon icon-filter"></i> </a>
            </div>
        </div>
    </div>

<div id="app-wrap">
    <div class="app-section st1 mt-1 bg_white_color">
        <div class="tf-container">
            <div class="wrap-total ">
                <div class="card col-sm-12 col-xl-6">
                    <div class="card-body">
                        <div class="">
                            <a href="#" class="box-icon bg_primary"><i class="icon-arrow-up_minor primary_color arrow-down"></i></a>
                            <div class="content">
                                <p class="fw_4">Income</p>
                                <h2 class="fw_6 success_color">₦{{number_format($all_income, 2)}}</h2>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card col-sm-12 col-xl-6">
                    <div class="card-body">
                        <div class="">
                            <a href="#" class="box-icon bg_critical"><i class="icon-arrow-up_minor critical_color arrow-up"></i></a>
                            <div class="content">
                                <p class="fw_4">Outcome</p>
                                <h2 class="fw_6 critical_color">₦{{number_format($all_outcome, 2)}}</h2>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <hr>


    <div class="app-section st1 mt-1 mb-5 bg_white_color">
        <div class="tf-container">
            <div class="trading-month">
                <h4 class="fw_5 mb-3">{{\Carbon\Carbon::now()->format('F')}}</h4>

                @foreach($transactions as $data)

                    @if($data->transaction_type == "PURCHASE")

                        <div class="group-trading-history mb-5">
                            <a class="tf-trading-history" href="open-transaction?id={{$data->id}}">
                                <div class="inner-left">
                                    <div class="icon-box rgba_primary">
                                        <i class="icon icon-credit-card2"></i>
                                    </div>
                                    <div class="content">
                                        <h4>POS PURCHASE</h4>
                                        <p>{{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <span class="num-val success_color">₦{{number_format($data->credit, 2)}}</span>
                            </a>

                        </div>

                    @elseif($data->transaction_type == "EnkPayTransfer")

                        <div class="group-trading-history mb-5">
                            <a class="tf-trading-history" href="open-transaction?id={{$data->id}}">
                                <div class="inner-left">
                                    <div class="icon-box rgba_primary">
                                        <i class="icon icon-credit-card2"></i>
                                    </div>
                                    <div class="content">
                                        <h4>Wallet Transfer</h4>
                                        <p>{{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @if($data->credit > 0)
                                    <span class="num-val success_color">₦{{number_format($data->credit, 2)}}</span>
                                @else
                                @endif
                            </a>

                        </div>

                    @elseif($data->transaction_type == "BankTransfer" && $data->type == "InterBankTransfer")

                        <div class="group-trading-history mb-5">
                            <a class="tf-trading-history" href="open-transaction?id={{$data->id}}">
                                <div class="inner-left">
                                    <div class="icon-box rgba_primary">
                                        <i class="icon icon-bank2"></i>
                                    </div>
                                    <div class="content">
                                        <h4>Bank Transfer</h4>
                                        <p>{{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @if($data->status == 1)
                                    <span class="num-val critical_color">₦{{number_format($data->debit, 2)}}</span>
                                @else
                                    <span class="num-val warning_color">₦{{number_format($data->debit, 2)}}</span>
                                @endif
                            </a>

                        </div>


                    @elseif($data->transaction_type == "BankTransfer" && $data->title == "Commission")

                        <div class="group-trading-history mb-5">
                            <a class="tf-trading-history" href="open-transaction?id={{$data->id}}">
                                <div class="inner-left">
                                    <div class="icon-box rgba_primary">
                                        <i class="icon icon-bank2"></i>
                                    </div>
                                    <div class="content">
                                        <h4>Commission</h4>
                                        <p>{{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <span class="num-val success_color">₦{{number_format($data->credit, 2)}}</span>
                            </a>

                        </div>

                    @elseif($data->transaction_type == "VirtualFundWallet")

                        <div class="group-trading-history mb-5">
                            <a class="tf-trading-history" href="open-transaction?id={{$data->id}}">
                                <div class="inner-left">
                                    <div class="icon-box rgba_primary">
                                        <i class="icon icon-wallet-filled-money-tool"></i>
                                    </div>
                                    <div class="content">
                                        <h4>Wallet Funding</h4>
                                        <p>{{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <span class="num-val success_color">₦{{number_format($data->credit, 2)}}</span>
                            </a>

                        </div>

                    @elseif($data->transaction_type == "Reversal")

                        <div class="group-trading-history mb-5">
                            <a class="tf-trading-history" href="open-transaction?id={{$data->id}}">
                                <div class="inner-left">
                                    <div class="icon-box rgba_primary">
                                        <i class="icon icon-back"></i>
                                    </div>
                                    <div class="content">
                                        <h4>Reversal</h4>
                                        <p>{{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <span class="num-val success_color">₦{{number_format($data->credit, 2)}}</span>
                            </a>

                        </div>

                    @else
                    @endif

                @endforeach


            </div>

        </div>
    </div>
</div>

<div class="bottom-navigation-bar">
    <div class="tf-container">
        <ul class="tf-navigation-bar">
            <li>
                <a class="fw_4 d-flex justify-content-center align-items-center flex-column" href="/dashboard"><i class="icon-home"></i> Home</a>
            </li>
            <li class="active">
                <a class="fw_6 d-flex justify-content-center align-items-center flex-column" href="58_history.html">
                    <i class="icon-history"></i> History</a>
            </li>
            <li>
                <a class="fw_4 d-flex justify-content-center align-items-center flex-column" href="40_qr-code.html">
                    <i class="icon-scan-qr-code"></i> </a>
            </li>
            <li>
                <a class="fw_4 d-flex justify-content-center align-items-center flex-column" href="62_rewards.html">
                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12.25" cy="12" r="9.5" stroke="#717171" />
                        <path
                            d="M17.033 11.5318C17.2298 11.3316 17.2993 11.0377 17.2144 10.7646C17.1293 10.4914 16.9076 10.2964 16.6353 10.255L14.214 9.88781C14.1109 9.87213 14.0218 9.80462 13.9758 9.70702L12.8933 7.41717C12.7717 7.15989 12.525 7 12.2501 7C11.9754 7 11.7287 7.15989 11.6071 7.41717L10.5244 9.70723C10.4784 9.80483 10.3891 9.87234 10.286 9.88802L7.86469 10.2552C7.59257 10.2964 7.3707 10.4916 7.2856 10.7648C7.2007 11.038 7.27018 11.3318 7.46702 11.532L9.2189 13.3144C9.29359 13.3905 9.32783 13.5 9.31021 13.607L8.89692 16.1239C8.86027 16.3454 8.91594 16.5609 9.0533 16.7308C9.26676 16.9956 9.6394 17.0763 9.93735 16.9128L12.1027 15.7244C12.1932 15.6749 12.3072 15.6753 12.3975 15.7244L14.563 16.9128C14.6684 16.9707 14.7807 17 14.8966 17C15.1083 17 15.3089 16.9018 15.4469 16.7308C15.5845 16.5609 15.6399 16.345 15.6033 16.1239L15.1898 13.607C15.1722 13.4998 15.2064 13.3905 15.2811 13.3144L17.033 11.5318Z"
                            stroke="#717171" stroke-width="1.25" />
                    </svg>
                    Rewards</a>
            </li>
            <li>
                <a class="fw_4 d-flex justify-content-center align-items-center flex-column" href="69_profile.html">
                    <i class="icon-user-outline"></i> Profile</a>
            </li>
        </ul>
    </div>
</div>

<div class="tf-panel up">
    <div class="panel-box panel-up panel-filter-history">
        <div class="header mb-1 is-fixed">
            <div class="tf-container">
                <div class="tf-statusbar d-flex justify-content-center align-items-center">
                    <a href="#" class="clear-panel"> <i class="icon-left"></i> </a>
                    <h3>Filter</h3>
                </div>
            </div>
        </div>
        <form id="app-wrap" class="style1" action="filter_transaction" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mt-6">
                <div class="tf-container">
                    <h4 class="mt-3 mb-3">Date From</h4>
                    <input class="form-control" type="date" name="from" >
                </div>

                <div class="tf-container">
                    <h4 class="mt-3 mb-3">Date To</h4>
                    <input class="form-control" type="date" name="to" >
                </div>
            </div>

            <hr>


            <div class="mt-1">
                <div class="container">
                    <h4 class="mt-3 mb-3">Status</h4>
                    <select class="form-control" name="status">
                        <option value=" ">Any</option>
                        <option value="1">Successful</option>
                        <option value="0">Pending</option>
                        <option value="4">Reversed</option>
                    </select>
                </div>
            </div>

            <hr>

            <div class="tf-container">
                <h4 class="mt-3 mb-3">Customer Email</h4>
                <input class="form-control" type="email" name="email" >
            </div>


            <div class="box-btn">
                <div class="tf-container">
                    <button type="submit"  class="tf-btn accent large">Filter</button>

                </div>
            </div>

        </form>
        </div>




    </div>

</div>

@endsection
