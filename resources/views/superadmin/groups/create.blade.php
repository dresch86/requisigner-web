@extends('layout')
@push('css')
<link rel="stylesheet" href="{{ url('libs/holdon/holdon.min.css') }}">
<link rel="stylesheet" href="{{ url('libs/quill/quill-1.3.6.snow.css') }}">
@endpush
@push('scripts')
<script src="{{ url('libs/holdon/holdon.min.js') }}"></script>
<script src="{{ url('libs/quill/quill-1.3.6.min.js') }}"></script>
<script src="{{ url(mix('js/superadmin/groups/GroupCreateClient.js')) }}"></script>
@endpush
@section('breadcrumb-bar')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Groups</li>
        <li class="breadcrumb-item active" aria-current="page">Create Group...</li>
    </ol>
</nav>
@endsection
@section('content')
@include('partials.main-alert')
<form id="requisigner-create-group-form" action="{{ route('post-create-group-form') }}" method="post" enctype="multipart/form-data" autocomplete="off" class="container">
    @csrf
    <div class="row mb-3">
        <label for="requisigner-group-name" class="col-sm-2 col-form-label">Group Name<span class="text-danger">*</span></label>
        <div class="col-lg-6">
            <input type="text" class="form-control" id="requisigner-group-name" name="group_name" required>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-6">
            <label for="requisigner-group-description" class="col-form-label">Description</label>
            <div id="requisigner-group-description" class="requisigner-rich-text-box"></div>
        </div>
    </div>
    <div class="d-flex flex-row justify-content-end">
        <button class="btn btn-primary btn-sm requisigner-btn-save">Save</button>
    </div>
</form>
@endsection