@extends('admin.layouts')

@section('title', 'User Management - View User Details')

@section('header')
    <!-- Include necessary CSS files -->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/formILY.css')}}">
@endsection

@section('content')
    {{-- <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12">
                                                                              <!-- Profile Image in the Top-Right Corner -->
<div class="col-lg-12">
    <div class="d-flex justify-content-end mb-3">
        @if($user->profile_image)
            <img src="{{ asset($user->profile_image) }}" alt="Profile Image" class="img-fluid rounded-circle border" width="120" height="120">
        @else
            <p>No profile image available.</p>
        @endif
    </div>
</div>
                <h2 class="mb-4">User Details</h2>

                <form>
                    <div class="row">



                        <!-- User Name -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="user_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="user_name" value="{{ $user->name }}" readonly>
                            </div>
                        </div>

                        <!-- User ID -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">User ID</label>
                                <input type="text" class="form-control" id="user_id" value="{{ $user->user_id }}" readonly>
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <input type="text" class="form-control" id="role" value="{{ ucfirst($user->role) }}" readonly>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" value="{{ $user->phone_number }}" readonly>
                            </div>
                        </div>
                       
                        <!-- Is Active -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="is_active" class="form-label">Active Status</label>
                                <input type="text" class="form-control" id="is_active" 
                                    value="{{ $user->is_active ? 'Active' : 'Inactive' }}" readonly>
                            </div>
                        </div>

                        <!-- DOB -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" value="{{ $user->dob }}" readonly>
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <input type="text" class="form-control" id="gender" value="{{ ucfirst($user->gender) }}" readonly>
                            </div>
                        </div>

                        <!-- Interests -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="interest" class="form-label">Interests</label>
                                <input type="text" class="form-control" id="interest" value="{{ $user->interest }}" readonly>
                            </div>
                        </div>

                        <!-- Country -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" value="{{ $user->country }}" readonly>
                            </div>
                        </div>

                       <!-- Language -->
<div class="col-lg-6">
    <div class="mb-3">
        <label for="language" class="form-label">Selected Languages</label>
        <input type="text" class="form-control" id="language" 
               value="{{ $user->selectedLanguages->pluck('language_name')->implode(', ') }}" readonly>
    </div>
</div>

                        
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12">
                
               

                <h2 class="mb-4">User Details</h2>

                <form>
                    <div class="row">
                        <!-- User Name -->
                        <div class="col-lg-6 mb-3">
                            <label for="user_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="user_name" value="{{ $user->name }}" readonly>
                        </div>

                         <!-- Profile Image in the Top-Right Corner -->
                        <div class="col-lg-6 mb-3">
                            <label for="image" class="form-label">Profile Image</label>
                        @if($user->profile_image)
                          <img src="{{ asset($user->profile_image) }}" alt="Profile Image" class="img-fluid rounded-circle border" width="120" height="120">
                              @else
                        <p>No profile image available.</p>
                           @endif
                         </div>

                        <!-- User ID -->
                        <div class="col-lg-6 mb-3">
                            <label for="user_id" class="form-label">User ID</label>
                            <input type="text" class="form-control" id="user_id" value="{{ $user->user_id }}" readonly>
                        </div>

                        <!-- Role -->
                        <div class="col-lg-6 mb-3">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" class="form-control" id="role" value="{{ ucfirst($user->role) }}" readonly>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-lg-6 mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" value="{{ $user->phone_number }}" readonly>
                        </div>

                        <!-- Is Active -->
                        <div class="col-lg-6 mb-3">
                            <label for="is_active" class="form-label">Active Status</label>
                            <input type="text" class="form-control" id="is_active" 
                                value="{{ $user->is_active ? 'Active' : 'Inactive' }}" readonly>
                        </div>

                        <!-- DOB -->
                        <div class="col-lg-6 mb-3">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" value="{{ $user->dob }}" readonly>
                        </div>

                        <!-- Gender -->
                        <div class="col-lg-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <input type="text" class="form-control" id="gender" value="{{ ucfirst($user->gender) }}" readonly>
                        </div>

                        <!-- Interests -->
                        <div class="col-lg-6 mb-3">
                            <label for="interest" class="form-label">Interests</label>
                            <input type="text" class="form-control" id="interest" value="{{ $user->interest }}" readonly>
                        </div>

                        <!-- Country -->
                        <div class="col-lg-6 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" value="{{ $user->country }}" readonly>
                        </div>

                        <!-- Language -->
                        <div class="col-lg-6 mb-3">
                            <label for="language" class="form-label">Selected Languages</label>
                            <input type="text" class="form-control" id="language" 
                                value="{{ $user->selectedLanguages->pluck('language_name')->implode(', ') }}" readonly>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <!-- Add any footer scripts if required -->
@endsection
