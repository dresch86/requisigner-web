@extends('layout')
@push('meta')
<meta name="csrf_token" content="{!! csrf_token() !!}" />
@endpush
@push('css')
<link rel="stylesheet" href="{{ url('libs/holdon/holdon.min.css') }}">
@endpush
@push('scripts')
<script src="{{ url('libs/holdon/holdon.min.js') }}"></script>
<script src="{{ url(mix('js/documents/TemplateFormClient.js')) }}"></script>
@endpush
@section('breadcrumb-bar')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Documents</li>
        <li class="breadcrumb-item">Templates Library</li>
        <li class="breadcrumb-item active" aria-current="page">{{ $template->name }}</li>
    </ol>
</nav>
@endsection
@section('content')
@include('partials.main-alert')
<div class="d-flex flex-column">

</div>
@endsection