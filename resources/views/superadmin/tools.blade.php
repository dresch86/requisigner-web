@extends('layout')
@section('breadcrumb-bar')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Admin</li>
    </ol>
</nav>
@endsection
@section('content')
<div class="d-flex flex-row justify-content-between">
    <div class="w-25 d-flex flex-column p-5 border border-info border-2">
        <a class="text-center" href="{{ route('get-users') }}">Users</a>
    </div>
    <div class="w-25 d-flex flex-column p-5 border border-info border-2">
        <a class="text-center" href="{{ route('get-groups') }}">Groups</a>
    </div>
    <div class="w-25 d-flex flex-column p-5 border border-info border-2">
        <a class="text-center" href="">Files</a>
    </div>
    <div class="w-25 d-flex flex-column p-5 border border-info border-2">
        <a class="text-center" href="{{ route('get-settings-form') }}">Settings</a>
    </div>
</div>
@endsection