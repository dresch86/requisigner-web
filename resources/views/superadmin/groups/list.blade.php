@extends('layout')
@push('meta')
<meta name="csrf_token" content="{!! csrf_token() !!}" />
@endpush
@push('css')
<link rel="stylesheet" href="{{ url('libs/holdon/holdon.min.css') }}">
@endpush
@push('scripts')
<script src="{{ url('libs/holdon/holdon.min.js') }}"></script>
@endpush
@section('breadcrumb-bar')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active" aria-current="page">Groups</li>
    </ol>
</nav>
@endsection
@section('content')
<div class="d-flex flex-row justify-content-end py-1">
    <a href="{{ route('get-create-group-form') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus-fill"></i>
    </a>
</div>
<table id="requisigner-group-list" class="table table-light table-striped text-center">
    <thead>
        <tr class="fw-bold table-dark">
            <th>&nbsp;</th>
            <th>Name</th>
            <th>Parent</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody class="align-middle">
    @foreach ($groups as $group)
        <tr>
            <td>
                <div class="d-flex flex-row">
                    <a href="{{ route('get-group-by-id', ['id' => $group->id]) }}" class="me-2">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    @if ($group->id == 1)
                    <i class="bi bi-person-x-fill text-secondary" data-control="disabled" data-user-id="-1"></i>
                    @else
                    <i class="bi bi-person-x-fill text-danger requisigner-cursor-pointer" data-control="delete" data-group-id="{{ $group->id }}"></i>
                    @endif
                </div>
            </td>
            <td>{{ $group->name }}</td>
            <td>{{ !is_null($group->parent) ? $group->parent->name : '--' }}</td>
            <td>{!! $group->description !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="d-flex flex-row justify-content-end">
    {{ $groups->links() }}
</div>
@endsection