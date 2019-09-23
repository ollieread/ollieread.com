@extends('users.admin.layout')

@section('breadcrumbs')
    <a href="{{ route('admin:dashboard') }}" class="breadcrumb">Admin</a>
    <span class="breadcrumb breadcrumb--active">Versions</span>
@endsection

@section('content')

    <header class="page__header">
        <div class="page__header-controls">
            <a href="{{ route('admin:version.create') }}" class="button button--primary button--small">Add new Version</a>
        </div>
        <h2 class="page__header-heading">Versions</h2>
    </header>

    @include('components.alerts', ['context' => 'admin.versions'])

    <section class="box box--headerless {{ ! $versions->hasPages() ? 'box--footerless' : '' }}">
        <main class="box__body box__body--flush">
            <table class="table">
                <thead class="table__header">
                <tr class="table__row">
                    <th class="table__cell">Name</th>
                    <th class="table__cell">Description</th>
                    <th class="table__cell">Docs</th>
                    <th class="table__cell">Release Date</th>
                    <th class="table__cell table__cell--actions"></th>
                </tr>
                </thead>
                <tbody class="table__body">
                @foreach ($versions as $version)
                    <tr class="table__row">
                        <td class="table__cell">
                            <span class="text--block">{{ $version->name }}</span>
                        </td>
                        <td class="table__cell text--secondary">
                            {{ $version->description }}
                        </td>
                        <td class="table__cell table__cell--center">
                            @if ($version->docs)
                                <a href="{{ $version->docs }}" class="link">Docs</a>
                            @else
                                --
                            @endif
                        </td>
                        <td class="table__cell table__cell--center">
                            @if ($version->release_date)
                                <span class="text--block">{{ $version->release_date->format('jS F Y') }}</span>
                            @else
                                --
                            @endif
                        </td>
                        <td class="table__cell table__cell--center">
                            <a href="{{ route('admin:version.edit', $version->id) }}" class="button button--icon button--small" data-tippy-content="Edit version">
                                <span class="sr-only">Edit</span>
                                <i class="button__icon fa-edit"></i>
                            </a>
                            <a href="{{ route('admin:version.delete', $version->id) }}" class="button button--icon button--small" data-tippy-content="Delete version">
                                <span class="sr-only">Delete</span>
                                <i class="button__icon fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </main>
        @if ($versions->hasPages())
            <footer class="box__footer box__footer--secondary">
                {!! $versions->links('partials.pagination') !!}
            </footer>
        @endif
    </section>

@endsection
