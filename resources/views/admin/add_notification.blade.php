@extends('admin.layouts')
@section('title', 'Notification')
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
        <div class="d-flex align-items-center">
            <h3 class="page-top-heading">Notification</h3>
        </div>
    </div>

    <div class="row d-flex justify-content-center mt-4">
        <div class="col-lg-6">
            <div class="card ILY-form-card">
                <div class="card-header">
                    <h5 class="card-title">Create Notification</h5>
                </div>
                <div class="card-body">
                    {{-- //form starts --}}
                    <form action="{{ route('notification_post') }}" method="POST">
                        @csrf  
                        <div class="row">
                            <div class="col-lg-12">
                               <div class="form-group mb-3">
                                    <label for="notification_title" class="form-label">Notification Title</label>
                                    <input type="text" name="notification_title" class="form-control" id="notification_title" value="{{ old('notification_title') }}">
                                    @error('notification_title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" class="form-control" id="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="button-container mt-4">
                                    <button type="submit" class="btn btn-primary submit-btn"><i class="fa-regular fa-circle-check me-2"></i>Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                       {{-- //form ends --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Table Row -->
    <div class="row mt-5">
        <div class="col-lg-12">
           <div class="mb-3">
               <h6 class="table-heading">History</h6>
           </div>
            <div class="card datatable-card">
                <div class="card-body">
                <table id="data-table" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>TITLE</th>
                                <th>DESCRIPTION</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($notifications as $notification)
                            <tr>
          
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $notification->title }}</td>
                              <td>{{ $notification->description }}</td>
                          
                              <td>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#DeleteModal-{{ $notification->id }}" class="btn transparent-btn text-danger text-decoration-underline">Delete</button>
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



 <!-- Delete Modal for Each Notification -->
@foreach ($notifications as $notification)
<div class="modal fade" id="DeleteModal-{{ $notification->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          
            <div class="modal-body">
                <div class="text-center">
                    <img class="mb-3" src="{{ asset('/assets') }}/images/icons/delete-icon.svg" alt="Delete Icon">
                    <p class="my-4">Are You Sure?</p>
                    <p class="text-muted my-2">Are you sure you want to delete this notification?</p>
                </div>
                <div class="d-flex align-items-center mt-5 mb-3">
                    <button data-bs-dismiss="modal" class="btn btn-light cancel-btn me-3">Cancel</button>
                
                <a href="{{ route('notification_delete', $notification->id) }}" class="btn btn-primary yes-btn"><i class="fa-regular fa-circle-check me-2"></i>Yes, Delete It!</a>
                            
            </div>
               
             
            </div>
        </div>
    </div>
</div>
@endforeach
<!-- Delete Modal for Each Notification 


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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection


