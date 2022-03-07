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
        <li class="breadcrumb-item active" aria-current="page">View Template</li>
    </ol>
</nav>
@endsection
@section('content')
@include('partials.main-alert')
<div class="d-flex flex-column h-100">
    <p>Please fill out the necessary fields below. Once the form has been filled out and submitted, your digital signature can be applied using the <a href="{{ route('get-docs-signing') }}">signing tool panel</a>. All documents must be filled out and submitted before signing takes place.</p>
    <iframe src="{{ route('get-template-pdf-viewer', ['id' => $template_id]) }}" title="PDF Viewer" class="h-100"></iframe>
    <div class="d-flex flex-row justify-content-end mt-2">
        <button class="btn btn-primary btn-sm requisigner-btn-save me-2">Select Signatures</button>
        <button class="btn btn-primary btn-sm requisigner-btn-save">Save</button>
    </div>
</div>
@endsection