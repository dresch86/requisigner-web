@extends('layout')
@push('meta')
<meta name="csrf_token" content="{!! csrf_token() !!}" />
@endpush
@push('css')
<link rel="stylesheet" href="{{ url('libs/holdon/holdon.min.css') }}">
@endpush
@push('scripts')
<script>
    const REQUISIGNER_USER_SEARCH_URL = "{{ route('post-users-search') }}";
</script>
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
        <button class="btn btn-primary btn-sm requisigner-btn-sigs me-2">Add Signees</button>
        <button class="btn btn-primary btn-sm requisigner-btn-save">Save</button>
    </div>
</div>
<div id="requisigner-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Signees</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>NOTE: The signature order below<strong>{!! ($version->enforce_sig_order == 1) ? ' will ' : ' not ' !!}</strong>be enforced!</p>
                <table id="requisigner-placeholder-list" class="border table table-light table-striped mb-1">
                    <thead>
                        <tr class="fw-bold table-dark text-center">
                            <th>Order</th>
                            <th>Title</th>
                            <th>Person</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle text-center">
                    @if($version->placeholders->count() > 0)
                    @foreach ($version->placeholders as $placeholder)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td class="text-center fw-bold">{{ (empty($placeholder->friendly_name)) ? $placeholder->pdf_name : $placeholder->friendly_name }}</td>
                            <td>
                                <input type="text" class="form-control" data-placeholder-id="{{ $placeholder->id }}" name="signee_{{ $placeholder->id }}" list="requisigner_signees_list">
                                <datalist id="requisigner_signees_list">
                                    <option value="Enter a name...">
                                </datalist>
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
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm requisigner-btn-done">Done</button>
            </div>
        </div>
    </div>
</div>
@endsection