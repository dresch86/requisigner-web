@extends('root')
@push('css')
<link rel="stylesheet" href="{{ url(mix('/css/app.css')) }}">
@endpush
@section('layout')
<div id="requisigner-page-wrapper" class="d-flex flex-row">
    <div id="requisigner-sidebar" class="d-none d-sm-flex flex-column">
        <a id="requisigner-main-home" href="{{ route('get-home') }}">
            <div class="d-flex flex-column justify-content-center align-items-center py-1">
                <i class="bi bi-vector-pen"></i>
                <span>{{ config('app.name') }}<span>
            </div>
        </a>
        @include('partials.nav-main')
    </div>
    <div id="requisigner-interactive-box" class="d-flex flex-column requisigner-flex-gsa-1">
        <div id="requisigner-account-box" class="d-flex flex-column">@include('partials.nav-header')</div>
        <div id="requisigner-workspace-box" class="d-flex flex-column px-3 requisigner-flex-gsa-1">
            <div>@yield('breadcrumb-bar')</div>
            <div>@yield('content')</div>
        </div>
        <div id="requisigner-footer-box" class="text-center p-3">&copy; {{ date('Y') }} <a href="https://www.requisigner.io">QuillSigner</a></div>
    </div>
</div>
@endsection