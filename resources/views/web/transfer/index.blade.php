@extends('layouts.app')
@section('content')

    <div class="header">
        <div class="tf-container">
            <div class="tf-statusbar d-flex justify-content-center align-items-center">
                <a href="#" class="back-btn"> <i class="icon-left"></i> </a>
                <h3>Transfer</h3>
            </div>
        </div>
    </div>
    <div class="content-by-bank mt-3">
        <div class="tf-container">
            <div class="heading">
                <h3 class="d-flex justify-content-between fw_6">Transfer To <a href="#">Scan QR
                        <i class="icon-right"></i></a></h3>
                <div class="tf-spacing-12"></div>
            </div>
            <form class="tf-form mt-3" action="process_bank_transfer" id="transfer" method="post">
                @csrf


                <input hidden name="bank_name" id="bankName">


                <div class="group-input">


                    <div class="box-custom-select">

                        <label for="">Account</label>
                        <select name="funds_account" required id="selectOption" class="form-control selectpicker"
                                data-live-search="true" data-width="100%"
                                style="background: rgb(243,245,255);border-radius: 10px;padding-top: 10px;padding-bottom: 10px;"
                                onchange="updateForm2()">
                            <option value="">Select Account</option>
                            @foreach ($account as $data)
                                <option value="{{$data['key']}}">
                                    {{$data['title']}} - ₦{{number_format($data['amount'], 2)}}
                                </option>
                            @endforeach
                        </select>

                        <input type="hidden" name="bank_name" id="bankName">


                    </div>

                </div>


                <div class="group-input">


                    <div class="box-custom-select">



                        <div class="custom-dropdown">
                            <div class="dropdown-btn" onclick="toggleDropdown()">Choose Bank</div>
                            <div class="dropdown-content">
                                <input type="text" name="bank_code" id="searchBank" onkeyup="filterBanks()" placeholder="Search bank...">
                                <ul id="bankList">

                                    @if($baanky == "ttmfb")
                                        @foreach ($banks as $data)
                                            <li onclick="selectBank('{{$data->code}}', '{{$data->bankName}}')">
                                                {{$data->bankName}}
                                            </li>
                                        @endforeach
                                    @elseif($baanky == "woven")

                                        @foreach ($banks as $data)
                                            <li onclick="selectBank('{{ $data['code'] }}', '{{ $data['bankName'] }}')">
                                                {{ $data['bankName'] }}
                                            </li>
                                        @endforeach

                                    @endif

                                </ul>
                            </div>
                        </div>




                        {{--                        <select name="bank_code" required data-live-search="true" id="selectOption"--}}
                        {{--                                onchange="updateForm2()" class="form-control mb-3" data-width="100%"--}}
                        {{--                                style="background: rgb(243,245,255);border-radius: 10px;padding-top: 10px;padding-bottom: 10px;">--}}
                        {{--                            <option value="">Select Banks</option>--}}
                        {{--                            @foreach ($banks as $data)--}}
                        {{--                                <option value="{{$data->code}}">{{$data->bankName}}</option>--}}
                        {{--                            @endforeach--}}
                        {{--                        </select>--}}


                    </div>

                </div>


                <input type="hidden" name="bank_code" id="bankCode">


                <div class="group-input">
                    <label for="">Account Number</label>
                    <input type="number" id="inputField" inputmode="numeric" name="acct_no" onkeyup="updateForm3()"
                           oninput="limitInputLength()" placeholder="Enter 10-digit account number" required>
                    <div class="credit-card">
                        {{--                        <span>Saved Number</span>--}}
                        <i class="icon-bankgroup"></i>
                    </div>

                </div>


                <div class="group-input">
                    <label for="">Account Name</label>
                    <input id="result" type="text" name="acct_name" value="" readonly required>
                </div>


                <div class="group-input input-field input-money">
                    <label for="">Amount</label>
                    <input type="text" name="amount" inputmode="numeric" value="₦ 100" required class="search-field value_input st1"
                           type="text">
                    <span class="icon-clear"></span>
                    <div class="money">
                        <a class="tag-money" href="#">₦ 1000</a>
                        <a class="tag-money" href="#">₦ 5000</a>
                        <a class="tag-money" href="#">₦ 100000</a>
                        <a class="tag-money" href="#">₦ 200000</a>
                    </div>
                </div>
                <div class="group-input">
                    <label for="">Narration</label>
                    <input type="text" name="narration" value=" " placeholder="Foods and Drinks">
                    <div class="tf-spacing-12"></div>
                    <div class="group-checkbox">
                        <input type="checkbox" class="tf-checkbox st1" checked>
                        <span class="fw_3">Save this account number to transfer money later</span>
                    </div>
                </div>

                <div id="loadingIndicator" style="display: none; color: rgb(0,0,0);">fetching
                    account...
                </div>


                <div class="mt-5 mb-9">
                    <div class="tf-container">
                        <div class="tf-title d-flex justify-content-between">
                            <h3 class="fw_6">Recent Transfer</h3>
                        </div>


                        <div class="app-section st1 mt-1 mb-5 bg_white_color">
                            <div class="tf-container" style="height:750px; width:100%; overflow-y: scroll;">


                                <div class="trading-month">

                                    @foreach($transfers as $data)

                                        @if($data->receiver_bank != null)

                                            <div class="group-trading-history mb-5">
                                                <a class="tf-trading-history" href="qtransfer?id={{$data->id}}">
                                                    <div class="inner-left">
                                                        <div class="icon-box rgba_primary">
                                                            <i class="icon icon-bank2"></i>
                                                        </div>
                                                        <div class="content">
                                                            <h4>{{$data->receiver_name}}</h4>
                                                            <p>{{$data->receiver_account_no}} | {{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}</p>
                                                        </div>
                                                    </div>

                                                    <svg width="50" height="50" viewBox="0 0 115 115" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M23.2037 57.5072L18.5127 43.125L77.201 55.1593C79.7525 55.6816 79.7525 59.328 77.201 59.8551L18.5103 71.8846L23.2037 57.5072Z" fill="url(#paint0_radial_679_2)"/>
                                                        <path d="M17.3985 10.1679C13.1172 8.02842 8.37823 12.1133 9.86125 16.663L19.4781 46.1437C19.7501 46.9766 20.2453 47.7189 20.9097 48.29C21.5742 48.8611 22.3825 49.2391 23.2468 49.3829L61.2447 55.7151C63.2452 56.0505 63.2452 58.9255 61.2447 59.2609L23.2492 65.5931C22.3849 65.7369 21.5766 66.1149 20.9121 66.686C20.2477 67.2571 19.7525 67.9994 19.4805 68.8323L9.86125 98.3298C8.37583 102.882 13.1172 106.967 17.3985 104.827L102.436 62.3204C106.411 60.3319 106.411 54.6609 102.436 52.6748L17.3985 10.1679Z" fill="url(#paint1_linear_679_2)"/>
                                                        <path d="M17.3985 10.1679C13.1172 8.02842 8.37823 12.1133 9.86125 16.663L19.4781 46.1437C19.7501 46.9766 20.2453 47.7189 20.9097 48.29C21.5742 48.8611 22.3825 49.2391 23.2468 49.3829L61.2447 55.7151C63.2452 56.0505 63.2452 58.9255 61.2447 59.2609L23.2492 65.5931C22.3849 65.7369 21.5766 66.1149 20.9121 66.686C20.2477 67.2571 19.7525 67.9994 19.4805 68.8323L9.86125 98.3298C8.37583 102.882 13.1172 106.967 17.3985 104.827L102.436 62.3204C106.411 60.3319 106.411 54.6609 102.436 52.6748L17.3985 10.1679Z" fill="url(#paint2_linear_679_2)"/>
                                                        <defs>
                                                            <radialGradient id="paint0_radial_679_2" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(14.375 57.5) scale(34.7396 4.64193)">
                                                                <stop stop-color="#0094F0"/>
                                                                <stop offset="1" stop-color="#2052CB"/>
                                                            </radialGradient>
                                                            <linearGradient id="paint1_linear_679_2" x1="9.58334" y1="-29.3466" x2="91.6526" y2="79.7094" gradientUnits="userSpaceOnUse">
                                                                <stop stop-color="#3BD5FF"/>
                                                                <stop offset="1" stop-color="#0094F0"/>
                                                            </linearGradient>
                                                            <linearGradient id="paint2_linear_679_2" x1="57.5" y1="35.4128" x2="82.4215" y2="102.841" gradientUnits="userSpaceOnUse">
                                                                <stop offset="0.125" stop-color="#DCF8FF" stop-opacity="0"/>
                                                                <stop offset="0.769" stop-color="#FF6CE8" stop-opacity="0.7"/>
                                                            </linearGradient>
                                                        </defs>
                                                    </svg>

                                                </a>

                                            </div>


                                        @endif


                                    @endforeach


                                </div>
                            </div>
                        </div>


                    </div>
                </div>



                <div class="bottom-navigation-bar bottom-btn-fixed st2">

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


                        <button type="submit" class="tf-btn accent" id="submit-btn">
                            <span id="btn-text">Continue</span>
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
                                let form = document.getElementById("transfer");

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

            </form>

        </div>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
            console.log("Bank Name:", document.getElementById("bankName").value);
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fetch-jsonp/1.3.0/fetch-jsonp.min.js"></script>
    <script>
        window.onload = function () {
            document.getElementById('inputField').disabled = true;
        };

        function toggleDropdown() {
            document.querySelector(".dropdown-content").style.display = "block";
        }

        function filterBanks() {
            let input = document.getElementById("searchBank").value.toLowerCase();
            let banks = document.getElementById("bankList").getElementsByTagName("li");

            for (let i = 0; i < banks.length; i++) {
                let txtValue = banks[i].textContent || banks[i].innerText;
                banks[i].style.display = txtValue.toLowerCase().includes(input) ? "" : "none";
            }
        }



        function selectBank(code, name) {
            document.querySelector(".dropdown-btn").textContent = name;
            document.getElementById("bankCode").value = code;
            document.getElementById("bankName").value = name;

            document.querySelector(".dropdown-content").style.display = "none";
            updateForm2();
        }

        function updateForm2() {
            document.getElementById('inputField').disabled = false;
        }

        function limitInputLength() {
            const inputValue = document.getElementById('inputField').value;
            if (inputValue.length > 10) {
                document.getElementById('inputField').value = inputValue.slice(0, 10);
            }
        }

        function updateForm3() {
            const bankCode = document.getElementById("bankCode").value;
            const accountNumber = document.getElementById("inputField").value;

            if (accountNumber.length === 10) {
                document.getElementById('loadingIndicator').style.display = 'block';

                const proxyUrl = `/proxy?callback=handleResponse&bank_code=${bankCode}&account_number=${accountNumber}`;

                fetch(proxyUrl)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data.status);
                        if (data.status === true) {
                            document.getElementById('result').value = data.customer_name;
                        } else {
                            document.getElementById('result').value = JSON.stringify(data.message, null, 2);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    })
                    .finally(() => {
                        document.getElementById('loadingIndicator').style.display = 'none';
                    });
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener("click", function(event) {
            if (!event.target.closest(".custom-dropdown")) {
                document.querySelector(".dropdown-content").style.display = "none";
            }
        });
    </script>


@endsection
