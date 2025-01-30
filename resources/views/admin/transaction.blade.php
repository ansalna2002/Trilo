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
            <h3 class="page-top-heading">TRANSACTION HISTORY</h3>
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
                            <th>DATE</th>
                            <th>USER ID</th>
                            <th>NAME</th>
                            <th>PHONE NUMBER</th>
                            <th>SUBSCRIPTION PLAN</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $subscription)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $subscription->created_at->format('d-m-Y') }}</td>
                                <td>{{ $subscription->user_id }}</td>
                                <td>{{ $subscription->name?? 'N/A' }}</td>
                                <td>{{ $subscription->number }}</td>
                                <td>{{ $subscription->plan_name }} plan</td>
                                <td>
                                    <span style="color: {{ $subscription->status == 1 ? 'green' : 'red' }};">
                                        {{ $subscription->status == 1 ? 'success' : 'failed' }}
                                    </span>
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


