@extends('admin.layout')

@section('breadcrumbs')
    <a href="{{ route('admin:dashboard') }}" class="breadcrumb">Admin</a>
    <span class="breadcrumb breadcrumb--active">Topics</span>
@endsection

@section('content')

    <header class="page__header">
        <div class="page__header-controls">
            <a href="{{ route('admin:topic.create') }}" class="button button--primary button--small">Add new Topic</a>
        </div>
        <h2 class="page__header-heading">Topics</h2>
    </header>

    @include('components.alerts', ['context' => 'admin.topics'])

    <section class="box box--headerless {{ ! $topics->hasPages() ? 'box--footerless' : '' }}">
        <main class="box__body box__body--flush">
            <table class="table">
                <thead class="table__header">
                <tr class="table__row">
                    <th class="table__cell">Topic</th>
                    <th class="table__cell">Description</th>
                    <th class="table__cell table__cell--actions"></th>
                </tr>
                </thead>
                <tbody class="table__body">
                @foreach ($topics as $topic)
                    <tr class="table__row">
                        <td class="table__cell">
                            {{ $topic->name }}
                        </td>
                        <td class="table__cell">
                            {{ $topic->description }}
                        </td>
                        <td class="table__cell table__cell--center">
                            <a href="{{ route('admin:topic.edit', $topic->id) }}" class="button button--icon button--small" data-tippy-content="Edit topic">
                                <span class="sr-only">Edit</span>
                                <i class="button__icon fa-edit"></i>
                            </a>
                            <a href="{{ route('admin:topic.delete', $topic->id) }}" class="button button--icon button--small" data-tippy-content="Delete topic">
                                <span class="sr-only">Delete</span>
                                <i class="button__icon fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </main>
        @if ($topics->hasPages())
            <footer class="box__footer box__footer--secondary">
                {!! $topics->links('partials.pagination') !!}
            </footer>
        @endif
    </section>

@endsection
