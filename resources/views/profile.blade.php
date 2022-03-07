@extends('layout')
@push('scripts')
<script src="{{ url('libs/holdon/holdon.min.js') }}"></script>
<script src="{{ url(mix('js/misc/ProfileClient.js')) }}"></script>
@endpush
@section('breadcrumb-bar')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Profile</li>
        <li class="breadcrumb-item active" aria-current="page">{{ $user->username }}</li>
    </ol>
</nav>
@endsection
@section('content')
@include('partials.main-alert')
<p>The fields below constitute your user profile. You need only enter a password if you intend to change it. If you do not intend to change your password, the corresponding field should be left blank.</p>
<form id="requisigner-user-profile" action="{{ route('post-profile') }}" method="post" enctype="multipart/form-data" autocomplete="off">
    @csrf
    <div class="row mb-3">
        <label for="requisigner-human-name" class="col-sm-2 col-form-label">Group</label>
        <div class="col-lg-6 d-flex flex-row align-items-center">{{ $user->group->name }}</div>
    </div>
    <div class="row mb-3">
        <label for="requisigner-human-name" class="col-sm-2 col-form-label">Name<span class="text-danger">*</span></label>
        <div class="col-lg-6">
            <input type="text" class="form-control" id="requisigner-human-name" name="human_name" value="{{ $user->name }}" required>
        </div>
    </div>
    <div class="row mb-3">
        <label for="requisigner-email" class="col-sm-2 col-form-label">Email<span class="text-danger">*</span></label>
        <div class="col-lg-6">
            <input type="text" class="form-control" id="requisigner-email" name="email" value="{{ $user->email }}" required>
        </div>
    </div>
    <div class="row mb-3">
        <label for="requisigner-password" class="col-sm-2 col-form-label">Password</label>
        <div class="col-lg-6">
            <input type="password" class="form-control" id="requisigner-password" name="password">
        </div>
    </div>
    <div class="row mb-3">
        <label for="requisigner-password-confirm" class="col-sm-2 col-form-label">Password Confirm</label>
        <div class="col-lg-6">
            <input type="password" class="form-control" id="requisigner-password-confirm" name="password_confirm">
        </div>
    </div>
    <div class="row mb-3">
        <label for="requisigner-office-location" class="col-sm-2 col-form-label">Office Location</label>
        <div class="col-lg-6">
            <div class="d-flex flex-column">
                <input type="text" class="form-control" id="requisigner-office-location" name="office_location" value="{{ $user->office }}">
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="requisigner-office-phone" class="col-sm-2 col-form-label">Office Phone</label>
        <div class="col-lg-6">
            <div class="d-flex flex-row align-items-end">
                <input type="text" class="form-control" id="requisigner-office-phone" name="office_phone" value="{{ $user->phone }}">
                <span class="ms-2 me-1 fw-bold">x</span>
                <input type="text" class="form-control" id="requisigner-office-extension" name="office_extension" value="{{ $user->extension }}">
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="requisigner-office-fax" class="col-sm-2 col-form-label">Office Fax</label>
        <div class="col-lg-6">
            <div class="d-flex flex-column">
                <input type="text" class="form-control" id="requisigner-office-fax" name="office_fax" value="{{ $user->fax }}">
            </div>
        </div>
    </div>
    <div class="d-flex flex-row justify-content-end">
        <button class="btn btn-primary btn-sm requisigner-btn-save">Save</button>
    </div>
</form>
@endsection