@extends('layouts.app')
@section('content')

    <div class="header">
        <div class="tf-container">
            <div class="tf-statusbar d-flex justify-content-center align-items-center">
                <a href="24_transfer-by-bank.html#" class="back-btn"> <i class="icon-left"></i> </a>
                <h3>Set Pin</h3>
            </div>
        </div>
    </div>
    <div class="content-by-bank mt-3">
        <div class="tf-container">


            <div class="mt-5">
                <div class="tf-container">


                    <div class="mt-7 mb-6">
                        <p style="text-align: center" class=" ">Set your Transfer Pin to enable you proceed with your transfer </p>
                    </div>



                    <form class="tf-form-verify" method="post"  id="set-pin" action="set_pin">


                        @csrf
                        <div class="d-flex group-input-verify">
                            <input type="password" name="pin1" maxlength="1" pattern="[0-9]" class="input-verify" value="">
                            <input type="password" name="pin2" maxlength="1" pattern="[0-9]" class="input-verify" value="">
                            <input type="password" name="pin3" maxlength="1" pattern="[0-9]" class="input-verify" value="">
                            <input type="password" name="pin4" maxlength="1" pattern="[0-9]" class="input-verify" value="">
                        </div>


                        <button type="submit" class="tf-btn accent" id="submit-btn">
                            <span id="btn-text">Set Pin</span>
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
                                let form = document.getElementById("set-pin");

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
                    </form>
                </div>


                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const inputs = document.querySelectorAll(".input-verify");

                        inputs.forEach((input, index) => {
                            input.addEventListener("input", function () {
                                if (this.value.length === 1 && index < inputs.length - 1) {
                                    inputs[index + 1].focus(); // Move to next input
                                }
                            });

                            input.addEventListener("keydown", function (e) {
                                if (e.key === "Backspace" && index > 0 && this.value.length === 0) {
                                    inputs[index - 1].focus(); // Move to previous input on Backspace
                                }
                            });
                        });
                    });
                </script>


            </div>

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
