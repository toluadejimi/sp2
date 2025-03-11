@extends('layouts.app2')
@section('content')

    <div class="header">
        <div class="tf-container">
            <div class="tf-statusbar d-flex justify-content-center align-items-center">
                <a href="24_transfer-by-bank.html#" class="back-btn"> <i class="icon-left"></i> </a>
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
            <form class="tf-form mt-3" action="process_bank_transfer" method="post">
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
                                    @foreach ($banks as $data)
                                        <li onclick="selectBank('{{$data->code}}', '{{$data->bankName}}')">
                                            {{$data->bankName}}
                                        </li>
                                    @endforeach
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
                    <input type="number" id="inputField" name="acct_no" onkeyup="updateForm3()"
                           oninput="limitInputLength()" placeholder="Enter 10-digit account number">
                    <div class="credit-card">
                        {{--                        <span>Saved Number</span>--}}
                        <i class="icon-bankgroup"></i>
                    </div>

                </div>


                <div class="group-input">
                    <label for="">Account Name</label>
                    <input id="result" type="text" name="acct_name" value="" readonly>
                </div>


                <div class="group-input input-field input-money">
                    <label for="">Amount</label>
                    <input type="text" name="amount" value="₦ 100" required class="search-field value_input st1"
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

                <div class="bottom-navigation-bar bottom-btn-fixed st2">
                    <button type="submit" class="tf-btn accent large">Continue</button>
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
