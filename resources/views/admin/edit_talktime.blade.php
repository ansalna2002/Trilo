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
            <h3 class="page-top-heading">Edit Plan Details</h3>
        </div>
    </div>

    <div class="row d-flex justify-content-center mt-4">
        <div class="col-lg-6">
            <div class="card ILY-form-card">
                <div class="card-header">
                    <h5 class="card-title">Enter Plan Details</h5>
                </div>
                <div class="card-body">
                
                    <form action="{{ route('update_talktime', ['id' => $talktime->id]) }}" method="POST">
                        @csrf 
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for="plan" class="form-label">Plan</label>
                                    <input 
                                        type="text" 
                                        name="plan" 
                                        class="form-control" 
                                        id="plan" 
                                        value="{{ old('plan', $talktime->plan) }}" 
                                        placeholder="Enter Plan"
                                    >
                                    @error('plan')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select name="type" class="form-control" id="type">
                                        <option value="message" {{ old('type') == 'message' ? 'selected' : '' }}>Message</option>
                                        <option value="voice_call" {{ old('type') == 'voice_call' ? 'selected' : '' }}>Voice Call</option>
                                        <option value="video_call" {{ old('type') == 'video_call' ? 'selected' : '' }}>Video Call</option>
                                    </select>
                                    @error('type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="talk_time" class="form-label">Talk Time</label>
                                    <input 
                                        type="text" 
                                        name="talk_time" 
                                        class="form-control" 
                                        id="talk_time" 
                                        value="{{ old('talk_time', $talktime->talk_time) }}" 
                                        placeholder="Enter Talk Time"
                                    >
                                    @error('talk_time')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input 
                                        type="number" 
                                        name="amount" 
                                        class="form-control" 
                                        id="amount" 
                                        value="{{ old('amount', $talktime->amount) }}" 
                                        placeholder="Enter Amount"
                                    >
                                    @error('amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="available_days" class="form-label">Available Days</label>
                                    <input 
                                        type="number" 
                                        name="available_days" 
                                        class="form-control" 
                                        id="available_days" 
                                        value="{{ old('available_days', $talktime->available_days) }}" 
                                        placeholder="Enter Available Days"
                                    >
                                    @error('available_days')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="button-container mt-4">
                                    <button type="submit" class="btn btn-primary submit-btn">
                                        <i class="fa-regular fa-circle-check me-2"></i>Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Container Fluid -->


@endsection

@section('footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
