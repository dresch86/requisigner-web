@extends('layout')
@push('meta')
<meta name="csrf_token" content="{!! csrf_token() !!}" />
@endpush
@push('css')
<link rel="stylesheet" href="{{ url('libs/holdon/holdon.min.css') }}">
@endpush
@push('scripts')
<script>
    const REQUISIGNER_DELETE_TEMPLATE_URL = "{{ route('post-template-del') }}";
</script>
<script src="{{ url('libs/holdon/holdon.min.js') }}"></script>
<script src="{{ url(mix('js/documents/TemplatesListClient.js')) }}"></script>
@endpush
@section('breadcrumb-bar')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Documents</li>
        <li class="breadcrumb-item active" aria-current="page">Templates Library</li>
    </ol>
</nav>
@endsection
@section('content')
@include('partials.main-alert')
<div class="d-flex flex-row justify-content-end py-2">
    <div class="col-xs-3 pe-2">
        <input type="text" id="requisigner-search" class="form-control" name="requisigner_search" placeholder="Search string...">
    </div>
    <button type="button" class="btn btn-primary"><i class="bi bi-search"></i></button>
</div>
<div class="d-flex flex-column">
    <div>
        <table id="requisigner-templates-list" class="border table table-light table-striped">
            <thead>
                <tr class="fw-bold table-dark text-center">
                    <th style="width: 40px;">&nbsp;</th>
                    <th>Template</th>
                    <th style="width: 40px;">Add</th>
                    <th style="width: 40px;">Edit</th>
                </tr>
            </thead>
            <tbody class="align-middle">
            @if($templates->count() > 0)
            @foreach ($templates as $template)
                <tr data-template-id="{{ $template->id }}">
                    <td class="text-center">
                        <a href="{{ route('get-template-blank', ['id' => $template->id]) }}"><i class="bi bi-filetype-pdf requisigner-pdf-icon"></i></a>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-bold">{{ $template->name }} (v{{ $template->semver }})</span>
                            <span>Owner: {{ $template->owner_name }} / {{ $template->group_name }}</span>
                            <div class="d-flex flex-row">
                                <span class="d-inline-flex text-primary" role="button" data-control="show_description">View Description</span>
                                <span class="mx-2">|</span>
                                <span class="d-inline-flex text-primary" role="button" data-control="set_signatures">Set Signatures</span>
                            </div>
                            <span class="d-none">{!! $template->description !!}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <a href=""><i class="bi bi-file-earmark-plus display-6"></i></a>
                    </td>
                    <td class="text-center">
                        <a href=""><i class="bi bi-info-circle display-6"></i></a>
                    </td>
                </tr>
            @endforeach
            @else
                <tr class="requisigner-no-data text-center">
                    <td colspan="5">There are no templates available for you to use. Please <a href="{{ route('get-template-form') }}">upload</a> one!</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <div class="d-flex flex-row justify-content-end">
        {{ $templates->links() }}
    </div>
</div>
<div id="requisigner-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
@endsection