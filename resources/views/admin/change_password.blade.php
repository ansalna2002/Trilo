<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Set New Password</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
      overflow: scroll;
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    .body::-webkit-scrollbar {
      display: none;
    }

    .container {
      display: flex;
      flex-direction: row;
      width: 100%;
    }

    .left-side,
    .right-side {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .left-side {
      background-color: #ffff;
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
      background: #ffff;
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

    .form-group input:focus+label,
    .form-group input:not(:placeholder-shown)+label {
      top: -8px;
      font-size: 12px;
      color: #000B58;
    }

    .form-group .eye-icon {
      position: absolute;
      top: 50%;
      right: 16px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #888;
    }

    a.text-white {
      color: #fff;
      text-decoration: none;
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
        <h2>Set a New Password</h2>
        <div class="mb-4" style="margin-bottom:30px;">
          <p>Your previous password has been reset. Please set a new password for your account.</p>
        </div>
        <form method="POST" action="{{ route('reset_password_update') }}">
          @csrf
          <input type="hidden" name="email" value="{{ $email }}">
          <input type="hidden" name="otp" value="{{ $otp }}">
       
          <div class="form-group">
            <input type="password" id="password" name="password" required>
            <label for="password">Create Password</label>
            <i class="fa fa-eye eye-icon" id="toggle_password" onclick="togglePasswordVisibility('password')"></i>
          </div>
          <div class="form-group">
            <input type="password" id="password_confirmation" name="password_confirmation" required>
            <label for="password_confirmation">Confirm Password</label>
            <i class="fa fa-eye eye-icon" id="toggle_password_confirmation" onclick="togglePasswordVisibility('password_confirmation')"></i>
          </div>
          <button type="submit" class="submit-button">Set Password</button>
        </form>
      </div>
    </div>
    <div class="right-side" style="padding:0px;">
      <img src="{{ asset('/assets') }}/images/auth/forget.svg" alt="Forgot Password Illustration">
    </div>
  </div>


  <script>
    function togglePasswordVisibility(inputId) {
      var input = document.getElementById(inputId);
      var icon = document.getElementById('toggle_' + inputId);

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }
  </script>



</body>

</html>