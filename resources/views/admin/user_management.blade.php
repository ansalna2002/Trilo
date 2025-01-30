@extends('admin.layouts')
@section('title', 'User Management')
@section('header')

<!-- DataTable CSS -->
<link rel="stylesheet" type="text/css" href="{{asset('/assets')}}/css/datatable/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('/assets')}}/css/datatable/responsive.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('/assets')}}/css/datatable/buttons.dataTables.min.css">

<!-- Page CSS -->
<link rel="stylesheet" type="text/css" href="{{asset('/assets')}}/css/formILY.css">

@endsection
@section('content')



<div class="container-fluid page-wrapper category-container">

   <div class="row">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="page-top-heading">User Management</h3>
        </div>
    </div>
    <div class="row mb-4">
        <!-- Total Users -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card border-0 dashboard-widget-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-4 text-center">
                            <p class="mb-0 d-card-para-title">Total onlineUsers</p>
                            <h2 class="mb-0 d-card-inner-head">{{ $onlineUsers }}</h2>
                        </div>
                        {{-- <div class="d-card-icon">
                            <img class="dashboard-icon-img" src="{{asset('/assets/images/icons/total-users.svg')}}" alt="Total Users">
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Daily Users -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card border-0 dashboard-widget-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-4 text-center">
                            <p class="mb-0 d-card-para-title">Daily Users</p>
                            <h2 class="mb-0 d-card-inner-head">{{ $dailyUsers }}</h2>
                        </div>
                        {{-- <div class="d-card-icon">
                            <img class="dashboard-icon-img" src="{{asset('/assets/images/icons/daily-users.svg')}}" alt="Daily Users">
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Weekly Users -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card border-0 dashboard-widget-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-4 text-center">
                            <p class="mb-0 d-card-para-title">Weekly Users</p>
                            <h2 class="mb-0 d-card-inner-head">{{ $weeklyUsers }}</h2>
                        </div>
                        {{-- <div class="d-card-icon">
                            <img class="dashboard-icon-img" src="{{asset('/assets/images/icons/weekly-users.svg')}}" alt="Weekly Users">
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Monthly Users -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="card border-0 dashboard-widget-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-4 text-center">
                            <p class="mb-0 d-card-para-title">Monthly Users</p>
                            <h2 class="mb-0 d-card-inner-head">{{ $monthlyUsers }}</h2>
                        </div>
                        {{-- <div class="d-card-icon">
                            <img class="dashboard-icon-img" src="{{asset('/assets/images/icons/monthly-users.svg')}}" alt="Monthly Users">
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
       <!-- Table Row -->
       <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card datatable-card">
                    <div class="card-body">
                    <table id="data-table" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>USER ID</th>
                                <th>NAME</th>
                                <th>PHONE NUMBER</th>
                                <th>country</th>
                                <th>Created</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                              
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->user_id }}</td>
                                    <td>
                                        {{ $user->name }}
                                    </td>
                                    <td>{{ $user->phone_number }}</td>
                                    <td>{{ $user->country }}</td>
                                    <td>{{ \Carbon\Carbon::parse($user->created_at)->toDateString() }}</td>
                                    <td>
                                        <span style="color: {{ $user->is_active == 1 ? 'green' : 'red' }};">
                                            {{ $user->is_active == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    
                                    <td>
   <!-- View User with Eye Icon -->
   <a href="{{ route('view_user', ['id' => $user->id]) }}" class="text-primary me-2">
    <i class="fa-regular fa-eye"></i> <!-- You can also use the "eye" emoji here if needed -->
</a>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                    </div>
                </div>
            </div>
        </div>
        <!-- Table Row Ends -->


    
    
</div>
<!-- end container fluid -->




@endsection
@section('footer')


<!-- JQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!-- Datatable JS -->
<script type="text/javascript" src="{{asset('/assets')}}/js/datatable/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="{{asset('/assets')}}/js/datatable/datatables.init.js"></script>
<script type="text/javascript" src="{{asset('/assets')}}/js/datatable/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="{{asset('/assets')}}/js/datatable/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{asset('/assets')}}/js/datatable/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="{{asset('/assets')}}/js/datatable/buttons.html5.min.js"></script>
<script type="text/javascript" src="{{asset('/assets')}}/js/datatable/buttons.print.min.js"></script>
<script type="text/javascript" src="{{asset('/assets')}}/js/datatable/jszip.min.js"></script>
<script type="text/javascript" src="{{asset('/assets')}}/js/datatable/pdfmake.min.js"></script>
<script type="text/javascript" src="{{asset('/assets')}}/js/datatable/vfs_fonts.js"></script>



@endsection


