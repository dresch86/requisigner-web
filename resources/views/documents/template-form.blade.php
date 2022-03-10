@extends('layout')
@push('css')
<link rel="stylesheet" href="{{ url('libs/holdon/holdon.min.css') }}">
<link rel="stylesheet" href="{{ url('libs/quill/quill-1.3.6.snow.css') }}">
<link rel="stylesheet" href="{{ url('libs/filepond/filepond-4.29.1.min.css') }}">
@endpush
@push('scripts')
<script>
    const REQUISIGNER_UPLOAD_MAX_SIZE = "{{ \App\HelperFunctions::max_upload_size() }}";
</script>
<script src="{{ url('libs/holdon/holdon.min.js') }}"></script>
<script src="{{ url('libs/quill/quill-1.3.6.min.js') }}"></script>
<script src="{{ url('libs/filepond/filepond-plugin-file-validate-type.js') }}"></script>
<script src="{{ url('libs/filepond/filepond-plugin-file-validate-size.js') }}"></script>
<script src="{{ url('libs/filepond/filepond-4.29.1.min.js') }}"></script>
<script src="{{ url('libs/filepond/filepond.jquery.js') }}"></script>
<script src="{{ url(mix('js/documents/TemplateUploadClient.js')) }}"></script>
@endpush
@section('breadcrumb-bar')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Documents</li>
        <li class="breadcrumb-item active" aria-current="page">Upload</li>
    </ol>
</nav>
@endsection
@section('content')
@include('partials.main-alert')
<div class="d-flex flex-column">
    <p>Please choose a <strong>blank</strong> PDF form to upload. The upload tool should only be used for <strong>new</strong> document templates, and not to fill out existing forms or upload new versions. Please check the <a href="{{ route('get-templates') }}">Template Library</a> before uploading a new one.</p>
    <form id="requisigner-document-upload-form" action="{{ route('post-template-form') }}" method="post" enctype="multipart/form-data" autocomplete="off" class="container">
        @csrf
        <div class="row">
            <div class="col-12 col-lg-6">
                <div>
                    <label for="requisigner-document-upload" class="col-form-label">Name<span class="text-danger">*</span></label>
                    <input type="text" id="requisigner-document-name" name="document_name" class="form-control"/>
                </div>
                <div>
                    <label for="requisigner-document-upload" class="col-form-label">Version<span class="text-danger">*</span></label>
                    <div class="form-group row g-0">
                        <div class="form-group col-lg-4 g-0">
                            <div class="d-inline-flex">
                                <span class="d-flex flex-row align-items-end fw-bold me-1">v</span>
                                <input type="number" id="requisigner-document-ver-maj" class="form-control" name="document_major_ver" step="1" min="0" value="1"/>
                            </div>
                        </div>
                        <div class="form-group col-lg-4 g-0">
                            <div class="d-inline-flex">
                                <span class="d-flex flex-row align-items-end fw-bold mx-1">.</span>
                                <input type="number" id="requisigner-document-ver-min" class="form-control" name="document_minor_ver" step="1" min="0" value="0"/>
                            </div>
                        </div>
                        <div class="form-group col-lg-4 g-0">
                            <div class="d-inline-flex">
                                <span class="d-flex flex-row align-items-end fw-bold mx-1">.</span>
                                <input type="number" id="requisigner-document-ver-pat" class="form-control" name="document_patch_ver" step="1" min="0" value="0"/>                            
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="requisigner-document-description" class="col-form-label">Description</label>
                    <div id="requisigner-document-description" class="requisigner-rich-text-box"></div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="form-group row g-0">
                    <div class="form-group col-lg-3 g-0">
                        <label for="requisigner-document-shared" class="col-form-label">Group Share</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="requisigner-group-read" name="group_read" value="1">
                                <label class="form-check-label" for="requisigner-group-read">Read</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="requisigner-group-edit" name="group_edit" value="1">
                                <label class="form-check-label" for="requisigner-group-edit">Edit</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-3 g-0">
                        <label for="requisigner-document-shared" class="col-form-label">World Share</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="requisigner-subgroup-read" name="subgroup_read" value="1">
                                <label class="form-check-label" for="requisigner-group-read">Read</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="requisigner-subgroup-edit" name="subgroup_edit" value="1">
                                <label class="form-check-label" for="requisigner-group-edit">Edit</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="requisigner-document-metatags" class="col-form-label">Metatags</label>
                    <textarea class="form-control" id="requisigner-document-metatags" name="document_metatags" placeholder="Comma separated list..." rows="4"></textarea>
                </div>
                <div>
                    <label for="requisigner-document-upload" class="col-form-label">Select Document...<span class="text-danger">*</span></label>
                    <div class="p-1 requisigner-uploads-box">
                        <input type="file" id="requisigner-document-upload" name="document_file" required/>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-end mt-1">
            <button class="btn btn-primary btn-sm requisigner-btn-save">Upload</button>
        </div>
    </form>
</div>
@endsection