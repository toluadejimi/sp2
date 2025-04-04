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



                </div>



                <div class="group-input">
                    <label for="">Account Number</label>
                    <input type="number" id="inputField" value="{{$trx->receiver_account_no}}" readonly  name="acct_no">
                </div>


                <div class="group-input">
                    <label for="">Account Name</label>
                    <input type="text" name="acct_name" value="{{$trx->receiver_name}}" readonly required>
                    <input type="text" name="bank_code" value="{{$trx->receiver_bank}}" hidden>

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
                </div>

                <div id="loadingIndicator" style="display: none; color: rgb(0,0,0);">fetching
                    account...
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
