@extends('layout')
@push('meta')
<meta name="csrf_token" content="{!! csrf_token() !!}" />
@endpush
@push('css')
<link rel="stylesheet" href="{{ url('libs/holdon/holdon.min.css') }}">
@endpush
@push('scripts')
<script>
    const REQUISIGNER_VSIG_CPTS_URL = "{{ route('get-visible-signature-cpts', ['id' => $user_id]) }}";
    const REQUISIGNER_VSIG_HANDLER_URL = "{{ route('post-visible-signature', ['id' => $user_id]) }}";
</script>
<script src="{{ url('libs/holdon/holdon.min.js') }}"></script>
<script src="{{ url('libs/signature-pad/signature_pad.umd.min.js') }}"></script>
<script src="{{ url('libs/sodium-plus/sodium-plus.min.js') }}"></script>
<script src="{{ url(mix('js/signatures/SignatureClient.js')) }}"></script>
@endpush
@section('breadcrumb-bar')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Signatures</li>
    </ol>
</nav>
@endsection
@section('content')
@include('partials.main-alert')
<div>
    <p>This tool panel allows you to configure two types of signatures. The visisble signature is the representation of your signature in an image, and is added to each form you sign. The digital signature is a specialized entity that involves a private key that should be saved locally and a public key that is automatically added to each document to indicate that you signed it. Your private key should be stored locally and not shared since it is used to add your official signature to a PDF form. When you load a locally stored private key into this panel, it is <strong>NOT</strong> sent to the server.</p>
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-5">
                <div id="requisigner-sig-caption" class="font-weight-bold">Visible Signature</div>
                <div class="d-inline-flex flex-column justify-content-start">
                    <canvas id="requisigner-signature-pad"></canvas>
                    <div class="d-flex flex-row">
                        <button id="requisigner-vsig-clear-btn" class="w-50 btn btn-outline-primary requisigner-btn-bottom">Clear</button>
                        <button id="requisigner-vsig-save-btn" class="w-50 btn btn-outline-primary requisigner-btn-bottom">Save</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div id="requisigner-sig-caption" class="font-weight-bold">Digital Signature</div>
                <div class="d-flex flex-column justify-content-start">
                    <button id="requisigner-dsig-gen-btn" type="button" class="btn btn-outline-primary">Generate</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection