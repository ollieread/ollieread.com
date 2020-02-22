@extends('admin.layout')

@section('breadcrumbs')
    <a href="{{ route('admin:dashboard') }}" class="breadcrumb">Admin</a>
    <span class="breadcrumb breadcrumb--active">Redirects</span>
@endsection

@section('content')

    <header class="page__header">
        <div class="page__header-controls">
            <a href="{{ route('admin:redirect.create') }}" class="button button--primary button--small">Add new Redirect</a>
        </div>
        <h2 class="page__header-heading">Redirects</h2>
    </header>

    @include('components.alerts', ['context' => 'admin.redirects'])

    <section class="box box--headerless {{ ! $redirects->hasPages() ? 'box--footerless' : '' }}">
        <main class="box__body box__body--flush">
            <table class="table">
                <thead class="table__header">
                <tr class="table__row">
                    <th class="table__cell">From</th>
                    <th class="table__cell">To</th>
                    <th class="table__cell table__cell--actions"></th>
                </tr>
                </thead>
                <tbody class="table__body">
                @foreach ($redirects as $redirect)
                    <tr class="table__row">
                        <td class="table__cell">
                            {{ $redirect->from }}
                        </td>
                        <td class="table__cell">
                            {{ $redirect->to }}
                        </td>
                        <td class="table__cell table__cell--center">
                            <a href="{{ route('admin:redirect.edit', $redirect->id) }}" class="button button--icon button--small" data-tippy-content="Edit redirect">
                                <span class="sr-only">Edit</span>
                                <i class="button__icon fa-edit"></i>
                            </a>
                            <a href="{{ route('admin:redirect.delete', $redirect->id) }}" class="button button--icon button--small" data-tippy-content="Delete redirect">
                                <span class="sr-only">Delete</span>
                                <i class="button__icon fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </main>
        @if ($redirects->hasPages())
            <footer class="box__footer box__footer--secondary">
                {!! $redirects->links('partials.pagination') !!}
            </footer>
        @endif
    </section>

@endsection
