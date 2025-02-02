<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | TRILO </title>
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

    <style>
        .logo-widget {
            background: #D4EFFF;
            background-size: cover !important;
        }
    </style>

</head>

<body>
    <div class="page-content overflow-hidden min-vh-100 forgot-pwd-container">
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
                        <img src="{{ asset('/assets') }}/images/logo-horizontal.svg" class="my-3 d-none widget-logo"
                            alt="logo-horizontal">
                    </div>
                    <div class="text-start">
                        <h3 class="form-heading">Email Verification</h3>
                        <p class="py-2 form-slogan">Enter your email address and we will send a link to reset your
                            password.</p>
                    </div>

                    <!-- Form starts -->
                    <form id="forgot_pswrdpage" action="{{ route('forget_verifyotp') }}" method="POST"
                        autocomplete="off">
                        @csrf
                        <div class="input-group mb-4">
                            <span class="input-group-text">
                                <img src="{{ asset('/assets') }}/images/icons/envelope.svg" alt="Email Icon">
                            </span>
                            <input type="email" id="email"
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" placeholder="Email" required>
                            @error('email')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-2">
                            <div class="otp-container" id="otpContainer">
                                <button class="btn btn-warning send-otp-btn" type="button" name="action"
                                    value="send_otp">Send OTP</button>
                                <input type="text" id="send_otp"
                                    class="form-control send_otp @error('otp') is-invalid @enderror" name="send_otp"
                                    placeholder="Enter OTP">
                                @error('otp')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="send_otp_msg" style="display:none;"></div>
                        </div>

                        <div class="login-btn-container">
                            <button class="btn btn-primary auth-submit-btn w-100" type="submit" name="action"
                                value="verify_otp">Submit</button>
                        </div>
                        <div class="otp-actions text-center" id="otp-actions" style="display:none;">
                            <p class="resend-otp-timer">Resend OTP in <span>30sec</span></p>
                            <a href="#" id="resend_otp" style="display:none;">Resend OTP</a>
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



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
    <script>
        $(document).ready(function() {
            let countdownInterval;


            $('.send-otp-btn').on('click', function(event) {
                event.preventDefault();
                const sendOtpBtn = $(this);
                sendOtpBtn.text('Sending OTP...').addClass('disabled').prop('disabled', true);
                $('#send_otp_msg').hide();

                $.ajax({
                    url: "{{ route('forget_sendotp') }}",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        action: 'send_otp',
                        email: $('#email').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success") {
                            $('#send_otp_msg').text('OTP sent successfully!').css('color',
                                'green').show();
                            startCountdown(30);
                            $('#otp-actions').show();
                            $('#resend_otp').hide();

                            setTimeout(function() {
                                $('#send_otp_msg').fadeOut();
                            }, 3000);
                        } else {
                            $('#send_otp_msg').text(response.message).css('color', 'red')
                        .show();
                            resetSendOtpButton(sendOtpBtn);
                        }
                    },
                    error: function() {
                        $('#send_otp_msg').text('Error sending OTP. Please try again.').css(
                            'color', 'red').show();
                        resetSendOtpButton(sendOtpBtn);
                    }
                });
            });


            $('#resend_otp').on('click', function(event) {
                event.preventDefault();
                $('.send-otp-btn').trigger('click');
                $(this).hide();
            });


            function startCountdown(duration) {
                clearInterval(countdownInterval);
                let timer = duration;

                countdownInterval = setInterval(function() {
                    $('.resend-otp-timer').show();
                    $('.resend-otp-timer span').text(`${timer--} sec`);

                    if (timer < 0) {
                        clearInterval(countdownInterval);
                        $('.send-otp-btn').removeClass('disabled').text('Send OTP').prop('disabled', false);


                        $('.resend-otp-timer').hide();
                        $('.resend-otp-timer span').text('');
                        $('#resend_otp').show();
                    }
                }, 1000);
            }


            function resetSendOtpButton(button) {
                button.removeClass('disabled').text('Send OTP').prop('disabled', false);
            }
        });
    </script>




    <script type="text/javascript" src="{{ asset('/assets') }}/js/authentication.js"></script>
    <script type="text/javascript" src="{{ asset('/assets') }}/js/bootstrap/5.3.2/bootstrap.min.js"></script>

</body>

</html>
