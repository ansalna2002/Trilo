<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify Code</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      display: flex;
      height: 100vh;
    }

    .body {
  overflow: scroll; /* Allow scrolling */
  -ms-overflow-style: none;  /* For IE and Edge */
  scrollbar-width: none; /* For Firefox */
}

.body::-webkit-scrollbar {
  display: none; /* For Chrome, Safari, and Opera */
}

    .container {
      display: flex;
      flex-direction: row;
      width: 100%;
    }

    .left-side, .right-side {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .left-side {
      background-color:#ffff;
      text-align: left;
    }

    .left-side .logo {
      position: absolute;
      top: 20px;
      width: 120px;
    }

    .form-container {
      width: 100%;
      max-width: 400px;
    }

    h2 {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 20px;
      text-align: left;
    }
    a {
      text-decoration: none;
    }

    .form-group {
      position: relative;
      margin-bottom: 20px;
    }

    .form-group label {
      font-size: 14px;
      font-weight: 400;
      line-height: 21px;
      position: absolute;
      top: 12px;
      left: 16px;
      color: #888;
      transition: all 0.2s ease-in-out;
      background:#ffff;
      padding: 0 5px;
    }

    .form-group input {
      width: 100%;
      height: 48px;
      border: 1px solid #ccc;
      border-radius: 4px;
      padding: 10px 16px;
      font-size: 14px;
      outline: none;
      transition: border-color 0.2s ease-in-out;
    }

    .form-group input:focus {
      border-color: #000B58;
    }

    .form-group input:focus + label,
    .form-group input:not(:placeholder-shown) + label {
      top: -8px;
      font-size: 12px;
      color: #000B58;
    }

    .submit-button {
      width: 100%;
      height: 48px;
      background-color: #000B58;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      margin-bottom: 20px;
    }

    .submit-button:hover {
      background-color: #0056b3;
    }

    .back-to-login {
      text-align: left;
      font-size: 14px;
      margin-bottom: 15px;
      width: 100%;
    }

    .back-to-login a {
      color: #000B58;
      text-decoration: none;
    }

    .back-to-login a:hover {
      text-decoration: none;
    }

    .right-side {
      background-color: #ffff;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .right-side img {
      max-width: 100%;
      height: auto;
    }

    
    a.text-white {
    color: #fff;
    text-decoration: none;
}

  /* Toast */
  .toast {
      position: fixed;
      top: 50px;
      right: 0;
      padding: 7px 15px;
      color: #fff;
      border-radius: 5px;
      opacity: 1;
      transition: opacity 0.2s ease-in-out;
      animation: shake 0.5s ease-in-out forwards;
  }

   @keyframes shake {
      0% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-20px); }
      20%, 40%, 60%, 80% { transform: translateX(20px); }
      100% { transform: translateX(0); }
   }
  
  .success-toast, #success-toast{
     background-color: #55B938 !important;
     color: #fff !important;
  }

  .error-toast, #error-toast{
      background-color: #e55039 !important;
      color: #fff !important;
  }

  .toast .btn-close{
   position: relative;
   top: -13px;
   right: -10px;
   font-size: 10px;
  }

    /* Responsive styles for mobile devices */
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .right-side {
        display: none;
      }

      .left-side {
        width: 100%;
        padding: 20px;
      }

      .form-group input {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>
<div class="container">
  <div class="left-side">
    <img src="{{ asset('/assets') }}/images/auth/logo.svg" alt="Logo" class="logo">
    
    <div class="form-container">
      <h2>Verify Code</h2>
      <div class="mb-4" style="margin-bottom:30px;">
        <p>An authentication code has been sent to your email.</p>
      </div>
      <form method="POST" action="{{ route('forget_verifyotp') }}">
        @csrf
        <input type="hidden" name="email" value="{{ request('email') }}"> 
    
        <div class="form-group">
            <input type="text" id="verification_code" name="otp" required class="form-control">
            <label for="verification_code">Enter Code</label>
        </div>
    
        <div class="mb-4" style="margin-bottom:30px;">
            <p>Didn’t receive a code? 
              
              <a href="{{ route('forgot_resend_otp', ['email' => $email]) }}">Resend</a>
        </div>
        <button type="submit" class="submit-button">Submit</button>
    </form>
    




    </div>
  </div>
  <div class="right-side" style="padding:0px;">
    <img src="{{ asset('/assets') }}/images/auth/forget.svg" alt="Forgot Password Illustration">
  </div>
</div>



<!-- Success Toast -->
@if(session('success'))
<div class="toast success-toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
 <div class="d-flex justify-content-between align-items-center">
     <div>
         <i style="font-size: 20px;" class="fa-regular fa-circle-check"></i>
     </div>
     <div class="toast-body text-center">
         {{ session('success') }}
     </div>
     <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
 </div>
</div>
@endif
<!-- Error Toast -->
@if(session('error'))
<div class="toast error-toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
 <div class="d-flex justify-content-between align-items-center">
     <div>
         <i style="font-size: 20px;" class="fa-solid fa-circle-exclamation"></i>
     </div>
     <div class="toast-body text-center">
         {{ session('error') }}
     </div>
     <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
 </div>
</div>
@endif
<!-- Script to trigger toasts -->
<script>
 document.addEventListener("DOMContentLoaded", function() {
     @if(session('success'))
         var successToast   = document.querySelector(".success-toast");
         var bsSuccessToast = new bootstrap.Toast(successToast);
         bsSuccessToast.show();
     @endif

     @if(session('error'))
         var errorToast = document.querySelector(".error-toast");
         var bsErrorToast = new bootstrap.Toast(errorToast);
         bsErrorToast.show();
     @endif
 });
</script>



</body>
</html>
