@extends('layout')
@section('breadcrumb-bar')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Settings</li>
    </ol>
</nav>
@endsection
@section('content')
<p>The settings below are system wide and affect all users.</p>
<form id="requisigner-settings-form" action="{{ route('post-settings-form') }}" method="post" enctype="multipart/form-data" autocomplete="off">
    @csrf
    <div class="row mb-3">
        <label for="requisigner-organization-name" class="col-sm-2 col-form-label">Organization Name<span class="text-danger">*</span></label>
        <div class="col-lg-6">
            <input type="text" class="form-control" id="requisigner-organization-name" data-setting-id="{{ $settings->org_name[0] }}" name="org_name" value="{{ $settings->org_name[1] }}" required>
        </div>
    </div>
    <div class="row mb-3">
        <label for="requisigner-admin-email" class="col-sm-2 col-form-label">Admin Email<span class="text-danger">*</span></label>
        <div class="col-lg-6">
            <input type="text" class="form-control" id="requisigner-admin-email" data-setting-id="{{ $settings->admin_email[0] }}" name="admin_email" value="{{ $settings->admin_email[1] }}">
        </div>
    </div>
    <div class="d-flex flex-row justify-content-end">
        <button class="btn btn-primary btn-sm requisigner-btn-save">Save</button>
    </div>
</form>
@endsection