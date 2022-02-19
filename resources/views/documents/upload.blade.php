@extends('layout')
@push('css')
<link rel="stylesheet" href="{{ url('libs/holdon/holdon.min.css') }}">
<link rel="stylesheet" href="{{ url('libs/quill/quill-1.3.6.snow.css') }}">
<link rel="stylesheet" href="{{ url('libs/filepond/filepond-4.29.1.min.css') }}">
@endpush
@push('scripts')
<script>
    const QUILLSIGNER_UPLOAD_MAX_SIZE = "{{ \App\HelperFunctions::max_upload_size() }}";
</script>
<script src="{{ url('libs/holdon/holdon.min.js') }}"></script>
<script src="{{ url('libs/quill/quill-1.3.6.min.js') }}"></script>
<script src="{{ url('libs/filepond/filepond-plugin-file-validate-type.js') }}"></script>
<script src="{{ url('libs/filepond/filepond-plugin-file-validate-size.js') }}"></script>
<script src="{{ url('libs/filepond/filepond-4.29.1.min.js') }}"></script>
<script src="{{ url('libs/filepond/filepond.jquery.js') }}"></script>
<script src="{{ url(mix('js/documents/DocumentUploadClient.js')) }}"></script>
@endpush
@section('breadcrumb-bar')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Documents</li>
        <li class="breadcrumb-item active" aria-current="page">Upload</li>
    </ol>
</nav>
@endsection
@section('content')
@include('partials.main-alert')
<div class="d-flex flex-column">
    <p>Please choose a <strong>blank</strong> PDF form to upload. The upload tool should only be used for <strong>new</strong> document templates, and not to fill out existing forms. Please check the <a href="{{ route('get-docs-lib') }}">Document Library</a> before uploading a new document.</p>
    <form id="requisigner-document-upload-form" action="{{ route('post-docs-upload') }}" method="post" enctype="multipart/form-data" autocomplete="off" class="container">
        @csrf
        <div class="row">
            <div class="col-6 col-xs">
                <label for="requisigner-document-upload" class="col-sm-2 col-form-label">Name</label>
                <input type="text" id="requisigner-document-name" name="document_name"/>
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-xs">
                <label for="requisigner-document-upload" class="col-sm-2 col-form-label">Version</label>
                <div class="d-flex flex-row">
                    <span class="fw-bold">v</span>
                    <input type="number" id="requisigner-document-ver-maj" class="col-sm-2" name="document_major_ver" step="1" min="0" value="1"/>
                    <span class="fw-bold mx-1">.</span>
                    <input type="number" id="requisigner-document-ver-min" class="col-sm-2" name="document_minor_ver" step="1" min="0" value="0"/>
                    <span class="fw-bold mx-1">.</span>
                    <input type="number" id="requisigner-document-ver-pat" class="col-sm-2" name="document_patch_ver" step="1" min="0" value="0"/>
                </div>
            </div>
        </div>
        <div class="row-3">
            <label for="requisigner-document-shared" class="col-sm-2 col-form-label">Shared</label>
            <div class="col-sm-2">
                <select id="requisigner-document-shared" name="document_shared" class="form-select">
                    <option value="0" selected>No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
        </div>
        <div class="col">
            <label for="requisigner-document-description" class="col-sm-2 col-form-label">Description</label>
            <div id="requisigner-document-description" class="reserquestrian-rich-text-box"></div>
        </div>
        <div class="col mb-3">
            <label for="requisigner-document-upload" class="col-sm-2 col-form-label">Select Document...</label>
            <div class="p-1 requisigner-uploads-box">
                <input type="file" id="requisigner-document-upload" name="document_file" required/>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-end">
            <button class="btn btn-primary btn-sm requisigner-btn-save">Upload</button>
        </div>
    </form>
</div>
@endsection