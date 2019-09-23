@extends('users.admin.layout')

@section('breadcrumbs')
    <a href="{{ route('admin:dashboard') }}" class="breadcrumb">Admin</a>
    <span class="breadcrumb breadcrumb--active">Articles</span>
@endsection

@section('content')

    <header class="page__header">
        <h2 class="page__header-heading">Articles</h2>
    </header>

    <section class="box box--headerless {{ ! $articles->hasPages() ? 'box--footerless' : '' }}">
        <main class="box__body box__body--flush">
            <table class="table">
                <thead class="table__header">
                <tr class="table__row">
                    <th class="table__cell">Name</th>
                    <th class="table__cell">Post At</th>
                    <th class="table__cell table__cell--actions"></th>
                </tr>
                </thead>
                <tbody class="table__body">
                @foreach ($articles as $article)
                    <tr class="table__row">
                        <td class="table__cell">
                            {{ $article->name }}
                            @if ($article->active)
                                <i class="fa fa-check text--success" data-tippy-content="Article is active"></i>
                            @else
                                <i class="fa fa-times text--error" data-tippy-content="Article is inactive"></i>
                            @endif
                            @if ($article->private)
                                <i class="fa fa-lock text--success" data-tippy-content="Article is private"></i>
                            @endif
                        </td>
                        <td class="table__cell table__cell--center text--number text--secondary">
                            {{ $article->post_at ? $article->post_at->format('d/m/Y H:i') : '--' }}
                        </td>
                        <td class="table__cell table__cell--center">
                            <a href="{{ route('articles:article', $article->slug, false) }}"
                               class="button button--icon button--small" data-tippy-content="View article"
                               target="_blank">
                                <i class="button__icon fa-eye"></i>
                            </a>
                            <a href="{{ route('admin:article.edit', $article->id) }}"
                               class="button button--icon button--small" data-tippy-content="Edit article">
                                <span class="sr-only">Edit</span>
                                <i class="button__icon fa-edit"></i>
                            </a>
                            <a href="{{ '#' ?? route('admin:article.toggle', $article->id) }}"
                               class="button button--icon button--small"
                               data-tippy-content="{{ $article->active ? 'Disable' : 'Enable' }} article">
                                @if ($article->active)
                                    <span class="sr-only">Disable</span>
                                    <i class="button__icon fa-toggle-off"></i>
                                @else
                                    <span class="sr-only">Enable</span>
                                    <i class="button__icon fa-toggle-on"></i>
                                @endif
                            </a>
                            <a href="#" class="button button--icon button--small" data-tippy-content="Delete article">
                                <span class="sr-only">Delete</span>
                                <i class="button__icon fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </main>
        @if ($articles->hasPages())
            <footer class="box__footer box__footer--secondary">
                {!! $articles->links() !!}
            </footer>
        @endif
    </section>

    {!! $articles->links('partials.pagination') !!}

@endsection
