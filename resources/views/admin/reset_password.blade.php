@extends('admin.layouts')
@section('title', 'Reset Password')
@section('header')

    <!-- Page CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets') }}/css/formILY.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets') }}/css/authentication.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection
@section('content')




    <div class="container-fluid reset-pwd-container">
        <div class="row">
            <div class="">
                <h3 class="page-top-heading">Reset Password</h3>
            </div>
        </div>

        <div class="row d-flex justify-content-center mt-4">
            <div class="col-lg-7">
                <div class="card ILY-form-card">
                    <div class="card-header">
                        <p class="form-slogan" style="margin-bottom:10px !important;">Enter old password to reset your
                            password. </p>
                    </div>
                    <div class="card-body pt-0">

                        <form action="{{ route('reset_password_handle') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group mb-4 position-relative">
                                        <span class="input-group-text">
                                            <img src="{{ asset('/assets') }}/images/icons/lock.svg" alt="Lock Icon">
                                        </span>
                                        <input type="password" name="password" id="oldPassword" class="form-control"
                                            placeholder="Old Password" aria-label="Old Password"
                                            aria-describedby="OldPassword" autocomplete="off" required>
                                        <span class="toggle-password position-absolute"
                                            style="right: 20px; top: 20px; cursor: pointer;">
                                            <i class="fa-solid fa-eye-slash" id="toggleOldPassword"
                                                onclick="togglePassword('oldPassword', 'toggleOldPassword')"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-group mb-4 position-relative">
                                        <span class="input-group-text">
                                            <img src="{{ asset('/assets') }}/images/icons/lock.svg" alt="Lock Icon">
                                        </span>
                                        <input type="password" name="new_pwd" id="newPassword" class="form-control"
                                            placeholder="New Password" aria-label="New Password"
                                            aria-describedby="NewPassword" autocomplete="off" required>
                                        <span class="toggle-password position-absolute"
                                            style="right: 20px; top: 20px; cursor: pointer;">
                                            <i class="fa-solid fa-eye-slash" id="toggleNewPassword"
                                                onclick="togglePassword('newPassword', 'toggleNewPassword')"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-group mb-4 position-relative">
                                        <span class="input-group-text">
                                            <img src="{{ asset('/assets') }}/images/icons/lock.svg" alt="Lock Icon">
                                        </span>
                                        <input type="password" name="new_pwd_confirmation" id="confirmPassword"
                                            class="form-control" placeholder="Confirm Password"
                                            aria-label="Confirm Password" aria-describedby="ConfirmPassword"
                                            autocomplete="off" required>
                                        <span class="toggle-password position-absolute"
                                            style="right: 20px; top: 20px; cursor: pointer;">
                                            <i class="fa-solid fa-eye-slash" id="toggleConfirmPassword"
                                                onclick="togglePassword('confirmPassword', 'toggleConfirmPassword')"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary full-width-btn mt-3 mb-2"><i
                                            class="fa-regular fa-circle-check me-2"></i>Submit</button>
                                </div>
                            </div>
                        </form>

                        <!-- Form Ends -->
                    </div>
                </div>
                <!-- Card Ends -->
            </div>
        </div>

    </div>

@endsection

@section('footer')


    <!-- Page JS -->
    {{-- <script type="text/javascript" src="{{ asset('/assets/js/formFM.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/js/authentication.js') }}"></script> --}}
  

    <script>
        function togglePassword(inputId, iconId) {
    console.log(`Toggling password visibility for ${inputId}`);
    const passwordInput = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    } else {
        passwordInput.type = "password";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }
}

    </script>
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


@endsection
