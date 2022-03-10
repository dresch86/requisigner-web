@extends('layout')
@push('meta')
<meta name="csrf_token" content="{!! csrf_token() !!}" />
@endpush
@push('css')
<link rel="stylesheet" href="{{ url('libs/holdon/holdon.min.css') }}">
@endpush
@push('scripts')
<script src="{{ url('libs/holdon/holdon.min.js') }}"></script>
<script src="{{ url(mix('js/documents/VersionClient.js')) }}"></script>
@endpush
@section('breadcrumb-bar')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Documents</li>
        <li class="breadcrumb-item">Template</li>
        <li class="breadcrumb-item active" aria-current="page">{{ $version->template->name }} (v{{ $version->semver }})</li>
    </ol>
</nav>
@endsection
@section('content')
@include('partials.main-alert')
<div class="d-flex flex-column">
    <p>The setting below allow you to set order signing preferences for this version, and give a user friendly name to the PDF placeholders.</p>
    <form id="requisigner-template-version-form" action="{{ route('post-version', ['id' => $version->id]) }}" method="post" enctype="multipart/form-data" autocomplete="off" class="container g-0">
        <table id="requisigner-placeholder-list" class="border table table-light table-striped mb-1">
            <thead>
                <tr class="fw-bold table-dark text-center">
                    <th>PDF Field</th>
                    <th>Friendly Name</th>
                    <th>Order</th>
                </tr>
            </thead>
            <tbody class="align-middle">
            @if($version->placeholders->count() > 0)
            @foreach ($version->placeholders as $placeholder)
                <tr data-placeholder-id="{{ $placeholder->id }}">
                    <td class="text-center fw-bold">{{ $placeholder->pdf_name }}</td>
                    <td>
                        <input type="text" class="form-control requisigner-ph-friendly-name">
                    </td>
                    <td>
                        {!! $order_select_menu !!}
                    </td>
                </tr>
            @endforeach
            @else
                <tr class="requisigner-no-data text-center">
                    <td colspan="3">No digital signature placeholders were found in this PDF!</td>
                </tr>
            @endif
            </tbody>
        </table>
        <div class="d-flex flex-row justify-content-end">
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="requisigner-enforce-sig-order">Enforce Signature Order</label>
                <input class="form-check-input" type="checkbox" id="requisigner-enforce-sig-order" name="enforce_sig_order" value="1">
            </div>
        </div>
        <div class="d-flex flex-row justify-content-end mt-1">
            <button type="submit" class="btn btn-primary btn-sm requisigner-btn-save">Save</button>
        </div>
    </form>
</div>
@endsection