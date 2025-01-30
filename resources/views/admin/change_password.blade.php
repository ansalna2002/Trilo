<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Create Password | TRILO</title>
      <link rel="icon" href="{{asset('/assets')}}/images/favicon.ico" type="image/x-icon">
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" type="text/css" href="{{asset('/assets')}}/css/bootstrap/5.3.2/bootstrap.min.css">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="{{asset('/assets')}}/css/icons/fontawesome/css/fontawesome.css">
      <link rel="stylesheet"  href="{{asset('/assets')}}/css/icons/fontawesome/css/brands.css">
      <link rel="stylesheet"  href="{{asset('/assets')}}/css/icons/fontawesome/css/regular.css">
      <link rel="stylesheet"  href="{{asset('/assets')}}/css/icons/fontawesome/css/solid.css">
      <!-- Page CSS -->
      <link rel="stylesheet" type="text/css" href="{{asset('/assets')}}/css/style.css">
      <link rel="stylesheet" type="text/css" href="{{asset('/assets')}}/css/authentication.css">
      <style>
         .logo-widget{
         background:#D4EFFF;
         /* background:url('{{asset('/assets')}}/images/login-bg.svg'); */
         background-size: cover !important;
         }
      </style>
   </head>
   <body>
      <div class="page-content overflow-hidden min-vh-100 reset-pwd-container">
         <div class="row g-0 vh-100">
            <div class="col-xl-5 col-lg-5 col-md-6 col-sm-6 rightside-widget-col">
               <div class="p-lg-5 p-4 h-100 logo-widget">
                  <div class="d-flex justify-content-center align-items-center h-100">
                     <div class="">
                        <img src="{{asset('/assets')}}/images/login-frame.svg" style="height:400px;" class="inframe-logo" alt="logo frame"> 
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-7 col-lg-7 col-md-6 col-sm-6 d-flex justify-content-center align-items-center">
               <div class="py-3 px-lg-5 px-md-4 px-sm-4 px-4 form-container row">
                  <div class="d-flex justify-content-center mb-1">
                     <img src="{{asset('/assets')}}/images/logo-horizontal.svg" class="my-3 d-none widget-logo" alt="logo-horizontal">
                  </div>
                  <div class="text-start">
                     <h3 class="form-heading">Create New Password</h3>
                     <p class="py-2 form-slogan">Enter new password.</p>
                  </div>
                  <!-- Form Ends -->
                  <form id="resetPasswordForm" action="{{ route('reset_password_update') }}" method="POST">
                     @csrf
                    
                     <div class="input-group mb-4 position-relative">
                        <span class="input-group-text">
                        <img src="{{asset('/assets')}}/images/icons/lock.svg" alt="Lock Icon">
                        </span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="New Password" aria-label="New Password" aria-describedby="NewPassword" autocomplete="off" required>
                        <button type="button" id="toggler" class="btn transparent-btn"><i id="icon" class="fa-solid fa-eye-slash"></i></button>
                     </div>
                     <div class="input-group mb-3 position-relative">
                        <span class="input-group-text">
                        <img src="{{asset('/assets')}}/images/icons/lock.svg" alt="Lock Icon">
                        </span>
                       
                        <input type="password" name="password_confirmation" id="confirm_pwd" class="form-control" placeholder="Confirm Password" aria-label="Confirm Password" aria-describedby="ConfirmPassword" autocomplete="off" required>
                        <button type="button" id="toggler2" class="btn transparent-btn"><i id="icon2" class="fa-solid fa-eye-slash"></i></button>
                     </div>
                     <input type="hidden" name="email" value="{{ $email }}"> <!-- Ensure email is properly set in a hidden field or passed through the request -->
                     <small class="text-danger" id="pwd-condition">Password must have 8 character and include a special character (~`! @#$%^&*-_+={}[]|\;:"<>,./?)</small>
                     <div class="login-btn-container">
                        <button type="submit" class="btn btn-primary auth-submit-btn w-100">
                        Submit
                        </button>
                     </div>
                     <div class="otp-actions text-center" id="otp-actions" style="display:none;">
                        <p class="resend-otp-timer">Resend OTP in <span>05:00</span></p>
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

      <!-- ====================================================================================================== -->
      <!-- Page JS -->
      {{-- <script type="text/javascript" src="{{asset('/assets')}}/js/authentication.js"></script> --}}
      <script type="text/javascript" src="{{asset('/assets')}}/js/bootstrap/5.3.2/bootstrap.min.js"></script>
      <!-- ====================================================================================================== -->   
      <!-- Validation JS -->
      <script>
         // Toggle visibility for "New Password" field
         document.getElementById('toggler').addEventListener('click', function () {
             const passwordField = document.getElementById('password');
             const icon = document.getElementById('icon');
             if (passwordField.type === 'password') {
                 passwordField.type = 'text';
                 icon.classList.replace('fa-eye-slash', 'fa-eye');
             } else {
                 passwordField.type = 'password';
                 icon.classList.replace('fa-eye', 'fa-eye-slash');
             }
         });

         // Toggle visibility for "Confirm Password" field
         document.getElementById('toggler2').addEventListener('click', function () {
             const confirmPasswordField = document.getElementById('confirm_pwd');
             const icon2 = document.getElementById('icon2');
             if (confirmPasswordField.type === 'password') {
                 confirmPasswordField.type = 'text';
                 icon2.classList.replace('fa-eye-slash', 'fa-eye');
             } else {
                 confirmPasswordField.type = 'password';
                 icon2.classList.replace('fa-eye', 'fa-eye-slash');
             }
         });
      </script>
   </body>
</html>