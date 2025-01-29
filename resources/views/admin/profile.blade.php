@extends('admin.layouts')
@section('title', 'Profile')
@section('header')

 <!-- Page CSS -->
<link rel="stylesheet" type="text/css" href="{{asset('/assets')}}/css/formILY.css">

@endsection
@section('content')



<div class="container-fluid page-wrapper">
    <div class="row">
        <div class="">
              <h3 class="page-top-heading">Profile</h3> 
        </div>
    </div>

       <div class="row d-flex justify-content-center mt-4">
            <div class="col-lg-12">
                <div class="card ILY-form-card">
                    <div class="card-body pt-0">
                        <form action="{{ route('profile_update') }}" method="POST">
                            @csrf
                     
                        <div class="row">
                           <div class="col-lg-12 mt-3 mb-4">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="profile-img-wrapper">
                                         <img src="{{asset('/assets')}}/images/avatar/profile.png" alt="Profile Image" id="profile-img-view" class="img-responsive">
                                    </div>
                                </div>
                           </div>
                           <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="admin_id" class="form-label">Admin ID</label>
                                    <input type="text" name="user_id" class="form-control" id="user_id" value="{{ old('user_id',$user->user_id) }}" placeholder="Admin ID">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $user->name) }}" placeholder="Name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $user->email) }}" placeholder="E-mail">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="pnumber" class="form-label">Admin ID</label>
                                    <input type="tel" name="phone_number" class="form-control" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="Phone Number">
                                </div>
                            </div>
                            <div class="col-lg-12 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary submit-btn mt-3 mb-2"><i class="fa-regular fa-circle-check me-2"></i>Update Profile</button>
                            </div>
                        </div>
                        <!-- Form Row Ends -->
                    </form>
                    <!-- Form Ends -->
                </div>
            </div>
            <!-- Card Ends -->
        </div>
    </div>

</div>
<!-- Container Fluid -->




@endsection
@section('footer')



@endsection


