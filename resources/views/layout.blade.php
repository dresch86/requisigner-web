@extends('root')
@push('css')
<link rel="stylesheet" href="{{ url(mix('/css/app.css')) }}">
@endpush
@section('layout')
<div id="quillsigner-page-wrapper" class="d-flex flex-row">
    <div id="quillsigner-sidebar" class="d-none d-sm-flex flex-column">
        <a id="quillsigner-main-home" href="{{ route('get-home') }}">
            <div class="d-flex flex-column justify-content-center align-items-center py-1">
                <i class="bi bi-vector-pen"></i>
                <span>{{ config('app.name') }}<span>
            </div>
        </a>
        @include('partials.nav-main')
    </div>
    <div id="quillsigner-interactive-box" class="d-flex flex-column quillsigner-flex-gsa-1">
        <div id="quillsigner-account-box" class="d-flex flex-column">@include('partials.nav-header')</div>
        <div id="quillsigner-workspace-box" class="d-flex flex-column px-3 quillsigner-flex-gsa-1">
            <div>@yield('breadcrumb-bar')</div>
            <div>@yield('content')</div>
        </div>
        <div id="quillsigner-footer-box" class="text-center p-3">&copy; {{ date('Y') }} <a href="https://www.quillsigner.io">QuillSigner</a></div>
    </div>
</div>
@endsection