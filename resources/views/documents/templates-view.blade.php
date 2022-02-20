@extends('layout')
@section('breadcrumb-bar')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Documents</li>
        <li class="breadcrumb-item active" aria-current="page">Templates Library</li>
    </ol>
</nav>
@endsection
@section('content')
<div class="d-flex flex-column">
    <div></div>
    <div>
        <table class="border table table-light table-striped">
            <thead>
                <tr class="fw-bold table-dark text-center">
                    <th>&nbsp;</th>
                    <th>Template</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
            @if($templates->count() > 0)
            @foreach ($templates as $template)
                <tr>
                    <td data-template-id="{{ $template->id }}"></td>
                    <td class="text-center">{{ $template->name }}</td>
                    <td>
                        <div class="d-flex flex-column">
                            <span>Version: v{{ $template->semver }}</span>
                            <span>Owner: {{ $template->owner_name }}</span>
                            <span>Description: {!! $template->description !!}</span>
                        </div>
                    </td>
                </tr>
            @endforeach
            @else
                <tr class="requisigner-no-data">
                    <td colspan="5">There are templates available for you to use. Please <a href="{{ route('get-template-form') }}">upload</a> one!</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <div class="d-flex flex-row justify-content-end">
        {{ $templates->links() }}
    </div>
</div>
@endsection