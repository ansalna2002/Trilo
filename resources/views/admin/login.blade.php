<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | TRILO</title>
    <link rel="icon" href="{{ asset('/assets') }}/images/favicon.ico" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets') }}/css/bootstrap/5.3.2/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/assets') }}/css/icons/fontawesome/css/fontawesome.css">
    <link rel="stylesheet" href="{{ asset('/assets') }}/css/icons/fontawesome/css/brands.css">
    <link rel="stylesheet" href="{{ asset('/assets') }}/css/icons/fontawesome/css/regular.css">
    <link rel="stylesheet" href="{{ asset('/assets') }}/css/icons/fontawesome/css/solid.css">

    <!-- Page CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets') }}/css/style.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets') }}/css/authentication.css">

    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .logo-widget {
            background: #D4EFFF;
            /* background:url('{{ asset('/assets') }}/images/login-bg.svg'); */
            background-size: cover !important;
        }

        #otp-actions {
            display: none;
            opacity: 0;
            position: relative;
            bottom: -20px;
            transition: opacity 0.5s ease-in-out, bottom 0.5s ease-in-out;
        }

        #otp-actions.show {
            opacity: 1;
            bottom: 0;
        }

        #otp-success-message {
            color: black;
            /* Default text color */
            font-weight: bold;
            /* Make text bold */
            margin-top: 10px;
            /* Space above the message */
        }

        #otp-error-message {
            color: black;
            /* Default text color */
            font-weight: bold;
            /* Make text bold */
            margin-top: 10px;
            /* Space above the message */
        }

        #otp-success-message.success {
            color: green;
            /* Success message color */
        }

        #otp-success-message.error {
            color: red;
            /* Error message color */
        }
    </style>

</head>

<body>
    <div class="page-content overflow-hidden min-vh-100 login-container">
        <div class="row g-0 vh-100">
            <div class="col-xl-5 col-lg-5 col-md-6 col-sm-6 rightside-widget-col">
                <div class="p-lg-5 p-4 h-100 logo-widget">
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="">
                            <img src="{{ asset('/assets') }}/images/login-frame.svg" style="height:400px;"
                                class="inframe-logo" alt="logo frame">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 col-lg-7 col-md-6 col-sm-6 d-flex justify-content-center align-items-center">
                <div class="py-3 px-lg-5 px-md-4 px-sm-4 px-4 form-container row">
                    <div class="d-flex justify-content-center mb-1">
                        <img src="{{ asset('/assets') }}/images/logo-horizontal.svg" class=" d-none widget-logo"
                            alt="logo-horizontal">
                    </div>
                    <div class="text-start">
                        <h3 class="login-heading">Login</h3>
                        <p class="py-2 login-slogan">Please fill your information below</p>
                    </div>



                    <form id="loginForm" action="{{ route('login_verifyotp') }}" method="POST"
                        class="row g-3 needs-validation" autocomplete="off">
                        @csrf
                        <!-- Email Input -->
                        <div class="input-group mb-4">
                            <span class="input-group-text">
                                <img src="{{ asset('/assets') }}/images/icons/envelope.svg" alt="Email Icon">
                            </span>
                            <input type="email" name="email" class="form-control" placeholder="E-mail"
                                aria-label="Email" aria-describedby="Email" required>
                        </div>

                        <!-- Password Input -->
                        <div class="input-group mb-3 position-relative">
                            <span class="input-group-text">
                                <img src="{{ asset('/assets') }}/images/icons/lock.svg" alt="Lock Icon">
                            </span>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Password" aria-label="Password" required>
                            <span class="toggle-password position-absolute"
                                style="right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                <i class="fa-solid fa-eye-slash" id="togglepassword"
                                    onclick="togglePassword('password', 'togglepassword')"></i>
                            </span>
                        </div>

                        <div class="d-flex justify-content-end align-items-center">
                            <a href="{{ route('forgot_password') }}" target="_self" class="forgot-pwd">Forgot
                                Password?</a>
                        </div>
                        <div id="send_msg" style="display:none;"></div>

                        <div class="mt-2">
                            <div class="otp-container " id="otpContainer">
                                <button type="button" class="btn btn-warning send-otp-btn" id="sendOtpBtn"
                                    onclick="showOtpInput()">Send OTP</button>
                                <input type="text" name="otp" class="form-control send_otp" id="otpInput"
                                    placeholder="Enter OTP">
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <a href="#" class="text-danger resend-otp-link" id="resend_otp"
                                style="display: none;">Resend OTP</a>
                            <span class="countdown-timer text-success">60 sec</span>
                        </div>

                        <!-- Login Button -->
                        <div class="login-btn-container mt-2">
                            <button type="submit" class="btn btn-success login-btn w-100">Login</button>
                        </div>
                    </form>


                    <!-- Form Ends -->




                </div>
            </div>
        </div>
    </div>

    {{-- toast --}}
    <!-- Success Toast -->
    @if (session('successmessage'))
        <div id="success-toast" class="toast bg-success align-items-center border-0 show" role="alert"
            aria-live="assertive" aria-atomic="true"
            style="position: fixed; top: 150px; right: 20px; z-index: 1050; border-radius: 8px;">
            <div class="toast-body d-flex text-white align-items-center">
                <i class="fa-regular fa-circle-check" style="font-size: 20px; margin-right: 10px;"></i>
                <div>
                    {{ session('successmessage') }}
                </div>
                <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Error Toast -->
    @if ($errors->any())
        <div id="error-toast" class="toast bg-danger align-items-center border-0 show" role="alert"
            aria-live="assertive" aria-atomic="true"
            style="position: fixed; top: 80px; right: 20px; z-index: 1050; border-radius: 8px;">
            <div class="toast-body d-flex text-white align-items-center">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 20px; margin-right: 10px;"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const successMessage = @json(session('successmessage'));
            const errorMessage = @json($errors->any() ? $errors->first() : null);

            function showAlertAndToast(type, message) {

                const isSuccess = type === 'success';
                const alertTitle = isSuccess ? 'Success' : 'Error';
                const alertText = message || (isSuccess ? 'Operation was successful!' : 'Something went wrong!');


                Swal.fire({
                    icon: type,
                    title: alertTitle,
                    text: alertText,
                    willClose: () => {

                        const toastElement = document.getElementById(`${type}-toast`);
                        if (toastElement) {
                            const toast = new bootstrap.Toast(toastElement);
                            toast.show();
                        }
                    }
                });
            }


            if (successMessage) {
                showAlertAndToast('success', successMessage);
            } else if (errorMessage) {
                showAlertAndToast('error', errorMessage);
            }


            document.getElementById('trigger-success').addEventListener('click', function() {
                showAlertAndToast('success', successMessage);
            });

            document.getElementById('trigger-error').addEventListener('click', function() {
                showAlertAndToast('error', errorMessage);
            });
        });
    </script>





    <script type="text/javascript" src="{{ asset('/assets') }}/js/authentication.js"></script>
    <script type="text/javascript" src="{{ asset('/assets') }}/js/bootstrap/5.3.2/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            let countdownInterval;
    
            // Trigger OTP send button
            $('#sendOtpBtn').on('click', function(event) {
                event.preventDefault();
                const sendOtpBtn = $(this);
                sendOtpBtn.text('Sending OTP...').addClass('disabled').prop('disabled', true);
                $('#send_otp_msg').hide();
    
                $.ajax({
                    url: "{{ route('forget_sendotp') }}",  // Ensure the correct route for sending OTP
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        email: $('#email').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success") {
                            $('#send_otp_msg').text('OTP sent successfully!').css('color', 'green').show();
                            startCountdown(30);
                            $('#otp-actions').show();
                            $('#resend_otp').hide();
    
                            setTimeout(function() {
                                $('#send_otp_msg').fadeOut();
                            }, 3000);
                        } else {
                            $('#send_otp_msg').text(response.message).css('color', 'red').show();
                            resetSendOtpButton(sendOtpBtn);
                        }
                    },
                    error: function() {
                        $('#send_otp_msg').text('Error sending OTP. Please try again.').css('color', 'red').show();
                        resetSendOtpButton(sendOtpBtn);
                    }
                });
            });
    
            // Countdown for OTP resend
            function startCountdown(duration) {
                clearInterval(countdownInterval);
                let timer = duration;
                const countdownElement = $('.resend-otp-timer');
    
                countdownInterval = setInterval(function() {
                    countdownElement.show();
                    countdownElement.find('span').text(`${timer--} sec`);
    
                    if (timer < 0) {
                        clearInterval(countdownInterval);
                        countdownElement.hide();
                        $('#resend_otp').show();
                    }
                }, 1000);
            }
    
            // Handle OTP resend click
            $('#resend_otp').on('click', function(event) {
                event.preventDefault();
                $('#sendOtpBtn').click(); // Trigger send OTP again
                $(this).hide();
            });
    
            // Reset the Send OTP button after error or success
            function resetSendOtpButton(button) {
                button.removeClass('disabled').text('Send OTP').prop('disabled', false);
            }
    
            // Handle OTP verification on form submission
            $('#forgot_pswrdpage').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission
    
                const otp = $('#send_otp').val(); // Get the OTP entered by the user
                const email = $('#email').val();  // Get the email address entered by the user
    
                if (!otp) {
                    $('#send_otp_msg').text('Please enter OTP.').css('color', 'red').show();
                    return; // Stop the submission if OTP is not entered
                }
    
                // Send the OTP for verification to the server
                $.ajax({
                    url: "{{ route('forget_verifyotp') }}",  // Ensure the correct route for verifying OTP
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        otp: otp,
                        email: email
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success") {
                            // OTP verification success, you can proceed to next step (e.g., reset password)
                            window.location.href = response.redirect_url;  // Redirect to reset password page or any other URL
                        } else {
                            $('#send_otp_msg').text(response.message).css('color', 'red').show();
                        }
                    },
                    error: function() {
                        $('#send_otp_msg').text('Error verifying OTP. Please try again.').css('color', 'red').show();
                    }
                });
            });
        });
    </script>
    

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Function to toggle password visibility
            function togglePassword(passwordFieldId, toggleIconId) {
                const passwordField = document.getElementById(passwordFieldId);
                const toggleIcon = document.getElementById(toggleIconId);

                if (passwordField.type === "password") {
                    passwordField.type = "text"; // Show password
                    toggleIcon.classList.remove("fa-eye-slash");
                    toggleIcon.classList.add("fa-eye");
                } else {
                    passwordField.type = "password"; // Hide password
                    toggleIcon.classList.remove("fa-eye");
                    toggleIcon.classList.add("fa-eye-slash");
                }
            }

            // Attach the togglePassword function to the click event of the toggle icon
            document.getElementById('togglepassword').addEventListener('click', function() {
                togglePassword('password', 'togglepassword');
            });
        });
    </script>
</body>

</html>
