@extends('root')
@push('css')
<link rel="stylesheet" href="{{ url(mix('css/login.css')) }}">
@endpush
@push('scripts')
<script src="{{ url(mix('js/login/LoginController.js')) }}"></script>
@endpush
@section('layout')
<div id="requisigner-login-screen" class="d-flex flex-column justify-content-center align-items-center">
    <div id="requisigner-login-portal" class="d-flex flex-column">
        <div id="requisigner-logo" class="d-flex flex-row justify-content-center align-items-end">
            <i class="bi bi-vector-pen display-1"></i>
        </div>
        <a id="requisigner-google-idp-btn">
            <div id="google-icon"></div>
            <span id="google-link"></span>
        </a>
        <form id="requisigner-login-form" action="{{ url('login') }}" method="POST">
            @csrf
            <h3 class="form-title text-center">Requisigner</h3>
            @if ($errors->any())
            <div id="requisigner-message-box" class="alert alert-danger">
                {!! implode('<br>', $errors->all()) !!}
            </div>
            @else
            <div id="requisigner-message-box" class="d-none"></div>
            @endif
            <div class="mb-3">
                <label class="control-label">Username</label>
                <input id="requisigner-username" class="form-control form-control-solid" type="text" autocomplete="off" placeholder="Username" name="username">
            </div>
            <div class="mb-3">
                <label class="control-label">Password</label>
                <input id="requisigner-password" class="form-control form-control-solid" type="password" autocomplete="off" placeholder="Password" name="password">
            </div>
            <div class="d-flex flex-row justify-content-end form-actions mb-3">
                <button type="submit" class="btn btn-secondary uppercase">Login</button>
            </div>
        </form>
    </div>
</div>
@endsection