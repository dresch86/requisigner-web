@extends('layout')
@push('meta')
<meta name="csrf_token" content="{!! csrf_token() !!}" />
@endpush
@push('css')
<link rel="stylesheet" href="{{ url('libs/holdon/holdon.min.css') }}">
@endpush
@push('scripts')
<script>
    var REQUISIGNER_DELETE_USER_URL = "{{ route('post-delete-user') }}";
</script>
<script src="{{ url('libs/holdon/holdon.min.js') }}"></script>
<script src="{{ url(mix('js/superadmin/users/UserDeleteClient.js')) }}"></script>
@endpush
@section('breadcrumb-bar')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active" aria-current="page">Users</li>
    </ol>
</nav>
@endsection
@section('content')
@include('partials.main-alert')
<div class="d-flex flex-row justify-content-end py-1">
    <a href="{{ route('get-create-user-form') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus-fill"></i>
    </a>
</div>
<table id="requisigner-user-list" class="table table-light table-striped text-center">
    <thead>
        <tr class="fw-bold table-dark">
            <th>&nbsp;</th>
            <th>User</th>
            <th>Name</th>
            <th>Group</th>
            <th>Contact</th>
            <th>Suspended</th>
        </tr>
    </thead>
    <tbody class="align-middle">
    @foreach ($users as $user)
        <tr>
            <td>
                <div class="d-flex flex-row">
                    <a href="{{ route('get-user-by-id', ['id' => $user->id]) }}" class="me-2">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    @if ($user->id != auth()->user()->id)
                    <i class="bi bi-person-x-fill text-danger requisigner-cursor-pointer" data-control="delete" data-user-id="{{ $user->id }}"></i>
                    @else
                    <i class="bi bi-person-x-fill text-secondary" data-control="disabled" data-user-id="{{ $user->id }}"></i>
                    @endif
                </div>
            </td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->group->name }}</td>
            <td>
                <div class="d-flex flex-column align-items-start">
                    <span>Office: {{ empty($user->office) ? '--' : $user->office }}</span>
                    <span>Phone: {{ empty($user->office) ? '--' : $user->phone }}{{ empty($user->extension) ? '' : (' x' . $user->extension) }}</span>
                </div>
            </td>
            <td>{{ ($user->suspended == 1) ? 'Yes' : 'No' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="d-flex flex-row justify-content-end">
    {{ $users->links() }}
</div>
<div id="requisigner-delete-confirm" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You are about to soft delete a user. Are you sure?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Yes</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection