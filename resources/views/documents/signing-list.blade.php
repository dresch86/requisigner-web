@extends('layout')
@section('breadcrumb-bar')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Documents</li>
        <li class="breadcrumb-item active" aria-current="page">Signing</li>
    </ol>
</nav>
@endsection
@section('content')
<div class="d-flex flex-column">
    <div>
        <table id="requisigner-documents-list" class="border table table-light table-striped">
            <thead>
                <tr class="fw-bold table-dark text-center">
                    <th>Document</th>
                    <th style="width: 40px;">Add</th>
                    <th style="width: 40px;">Edit</th>
                </tr>
            </thead>
            <tbody class="align-middle">
            @if($documents->count() > 0)
            @foreach ($documents as $document)
                <tr data-document-id="{{ $document->id }}">
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-bold">{{ $document->title }}</span>
                            <span>Requestor: {{ $document->owner->name }} / {{ $document->owner->group->name }}</span>
                            <div class="d-flex flex-row">
                                <span class="d-inline-flex text-primary" role="button" data-control="show_description">View Description</span>
                            </div>
                            <span class="d-none"></span>
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
                    <td colspan="5">There are no documents assigned to you.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <div class="d-flex flex-row justify-content-end">
        {{ $documents->links() }}
    </div>
</div>
@endsection