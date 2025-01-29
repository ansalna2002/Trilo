@extends('admin.layouts')
@section('title', 'Dashboard')

@section('header')
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

@section('content')

<div class="container-fluid page-wrapper dashboard-section">

    <div class="row">
        <div class="mb-4">
            <h3 class="page-top-heading">Dashboard</h3>
        </div>
    </div>
    
    <!-- Card Insights -->
        <div class="row">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 col-lg-custom">
                    <div class="card border-0 dashboard-widget-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-4 text-center">
                                        <p class="mb-0 d-card-para-title">TOTAL USER</p>
                                        <h2 class="mb-0 d-card-inner-head">{{ $userCount }}</h2>
                                    </div>
                                    <div class="d-card-icon">
                                        <img class="dashboard-icon-img" src="{{asset('/assets/images/icons/total-users.svg')}}" alt="User Count">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 col-lg-custom">
                    <div class="card border-0 dashboard-widget-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-4 text-center">
                                        <p class="mb-0 d-card-para-title">ACTIVE USER</p>
                                        <h2 class="mb-0 d-card-inner-head">{{ $is_activeCount }}</h2>
                                    </div>
                                    <div class="d-card-icon">
                                        <img class="dashboard-icon-img" src="{{asset('/assets/images/icons/total-users.svg')}}" alt="User Count">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{--             
                <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 col-lg-custom">
                    <div class="card border-0 dashboard-widget-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-4 text-center">
                                        <p class="mb-0 d-card-para-title">MATCHES MADE</p>
                                        <h2 class="mb-0 d-card-inner-head">{{ $userCount }}</h2>
                                    </div>
                                    <div class="d-card-icon">
                                        <img class="dashboard-icon-img" src="{{asset('/assets/images/icons/total-users.svg')}}" alt="User Count">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 col-lg-custom">
                    <div class="card border-0 dashboard-widget-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-4 text-center">
                                        <p class="mb-0 d-card-para-title">MESSAGES SENT</p>
                                        <h2 class="mb-0 d-card-inner-head">{{ $userCount }}</h2>
                                    </div>
                                    <div class="d-card-icon">
                                        <img class="dashboard-icon-img" src="{{asset('/assets/images/icons/total-users.svg')}}" alt="User Count">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
            
        </div>
        <!-- Row Ends.... -->

      


</div>
    <!-- container fluid -->
 
         


@endsection

@section('footer')
    <!-- JQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr JS -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Display toast based on session message -->
    <script>
        $(document).ready(function() {
            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif
        });
    </script>
@endsection
