@extends('admin.layouts')
@section('title', 'Plan Details')
@section('header')

<!-- Page CSS -->
<link rel="stylesheet" type="text/css" href="{{asset('/assets')}}/css/formILY.css">

@endsection
@section('content')

<div class="container-fluid page-wrapper category-container">
    <div class="row">
        <div class="d-flex align-items-center">
            <h3 class="page-top-heading">Security Mangement</h3>
        </div>
    </div>

    <div class="row d-flex justify-content-center mt-4">
        <div class="col-lg-6">
            <div class="card ILY-form-card">
                <div class="card-header">
                    <h5 class="card-title">Enter Plan Details</h5>
                </div>
                <div class="card-body">
                    {{-- Form Starts --}}
                    <form action="#" method="POST">
                        @csrf  
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for="voice" class="form-label">voice</label>
                                    <input type="text" name="voice" class="form-control" id="voice" placeholder="Enter voice">
                                    @error('voice')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                               
                                <div class="button-container mt-4">
                                    <button type="submit" class="btn btn-primary submit-btn"><i class="fa-regular fa-circle-check me-2"></i>Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    {{-- Form Ends --}}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Container Fluid -->
   <!-- Table Row -->
   <div class="row mt-5">
    <div class="col-lg-12">
       <div class="mb-3">
           <h6 class="table-heading">Security Management</h6>
       </div>
        <div class="card datatable-card">
            <div class="card-body">
            <table id="data-table" class="table nowrap align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>PLAN </th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    {{-- <tbody>
                        @foreach ($datatable as $table)
                        <tr>
      
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $table->plan }}</td>
                          <td>
                            <span style="color: {{ $table->status == 1 ? 'green' : 'red' }};">
                                {{ $table->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <!-- Edit Button -->
                            <a href="{{ route('edit_talktime', ['id' => $table->id]) }}" class="btn btn-primary text-white me-2">
                                <i class="fa-regular fa-pen-to-square me-2"></i>Edit
                            </a>
                        
                           <!-- Delete Button -->
                           <button type="button" data-bs-toggle="modal" data-bs-target="#DeleteModal-{{ $table->id }}" class="btn btn-danger">
                            <i class="fa-regular fa-trash-can me-2"></i>Delete
                        </button>

                        </td>
                        
                        </tr>
                      @endforeach
                    </tbody> --}}

                </table>

            </div>
        </div>
    </div>
</div>
<!-- Table Row Ends -->
{{-- @foreach ($datatable as $table)
<div class="modal fade" id="DeleteModal-{{ $table->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $table->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel-{{ $table->id }}">Delete Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img class="mb-3" src="{{ asset('/assets/images/icons/delete-icon.svg') }}" alt="Delete Icon">
                <p class="my-4">Are you sure?</p>
                <p class="text-muted my-2">Are you sure you want to delete the plan <strong>{{ $table->plan }}</strong>?</p>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <a href="{{ route('talktime_delete', ['id' => $table->id]) }}" class="btn btn-danger">
                    <i class="fa-regular fa-circle-check me-2"></i>Yes, Delete It!
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach --}}

@endsection

@section('footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
