@extends('admin.layouts')
@section('title', 'Banner')
@section('header')

<!-- DataTable CSS -->
<link rel="stylesheet" type="text/css" href="{{asset('/assets')}}/css/datatable/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('/assets')}}/css/datatable/responsive.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('/assets')}}/css/datatable/buttons.dataTables.min.css">

<!-- Page CSS -->
<link rel="stylesheet" type="text/css" href="{{asset('/assets')}}/css/formILY.css">
 <!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-oBqDVmMz4fnFO9t5a6P6A6O6A0p3U7v38wzA1N39N7Jt6jvNIi2B2i+1DkF1Vr" crossorigin="anonymous"></script>

@endsection
@section('content')



<div class="container-fluid page-wrapper category-container">

   <div class="row">
        <div class="d-flex align-items-center">
            <h3 class="page-top-heading">Language</h3>
        </div>
    </div>
 
    <div class="row d-flex justify-content-center mt-4">
        <div class="col-lg-6">
            <div class="card ILY-form-card">
                <div class="card-header">
                    <h5 class="card-title">Add Language</h5>
                </div>
                <div class="card-body">
                    {{-- //formstartsends --}}
                    <form action="{{ route('languages_store') }}" method="POST" enctype="multipart/form-data">
                        @csrf 
                        <div class="row">
                            <div class="col-lg-12">
                               <div class="form-group mb-3">
                                    <label for="banner_name" class="form-label">Language Name</label>
                                    <input type="text" name="banner_name" class="form-control" id="banner_name">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="banner_img" class="form-label">Choose Image</label>
                                    <input type="file" name="banner_img" class="form-control custom-file-input" id="banner_img">
                                </div>
                                <div class="button-container mt-4">
                                    <button type="submit" class="btn btn-primary submit-btn"><i class="fa-regular fa-circle-check me-2"></i>Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                     {{-- //formsends --}}
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
                                <th>DATE</th>
                                <th>LANGUAGE NAME</th>
                                <th>IMAGE </th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tbody>
                                @foreach ($banners as  $banner)
                                    <tr>
                                        <td>{{ $loop->iteration  }}</td>
                                        <td>{{ $banner->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $banner->name }}</td>
                                        <td>
                                            <img src="{{ asset($banner->image) }}" class="preview-td-img" alt="preview image">
                                        </td>
                                       
                                        <td>
                                             <!-- Delete Emoji -->
                            <a href="#" data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#DeleteModal-{{ $banner->id }}" class="text-danger">
                                🗑️
                            </a>
                                            {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#DeleteModal-{{ $banner->id }}" class="btn transparent-btn text-danger text-decoration-underline">Delete</button>
                                         --}}
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

<!-- Delete Modal for Each Banner -->
@foreach ($banners as $banner)
<div class="modal fade zoom-in" id="DeleteModal-{{ $banner->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="p-3">
                            <div class="text-center">
                                <img class="mb-3" src="{{asset('/assets')}}/images/icons/delete-icon.svg">
                                <p class="my-4 are-you-sure">Are You Sure?</p>
                                <p class="text-muted my-2 are-you-sure-subtext">Are you sure you want to delete this language?</p>
                            </div>
                            <div class="d-flex align-items-center mt-5 mb-3">
                                <button data-bs-dismiss="modal" class="btn btn-light cancel-btn me-3">Cancel</button>
                                <a href="{{ route('banner_delete', $banner->id) }}" class="btn btn-primary yes-btn"><i class="fa-regular fa-circle-check me-2"></i>Yes, Delete It!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
<!-- Delete Modal Ends -->



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