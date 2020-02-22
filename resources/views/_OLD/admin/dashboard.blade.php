@extends('admin.layout')

@section('breadcrumbs')
    <span class="breadcrumb breadcrumb--active">Admin</span>
@endsection

@section('content')

    <div class="box">
        <header class="box__header">
            <h3 class="box__header-title">Recent Comments</h3>
        </header>
        <main class="box__body box__body--flush">
            <table class="table">
                <thead class="table__header">
                <tr class="table__row">
                    <th class="table__cell">Author</th>
                    <th class="table__cell">At</th>
                </tr>
                </thead>
                <tbody class="table__body">
                @foreach ($comments as $comment)
                    <tr class="table__row">
                        <td class="table__cell">
                            {{ $comment->author->username }}
                        </td>
                        <td class="table__cell text--secondary">
                            {{ $comment->created_at->format('jS F Y H:i') }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </main>
    </div>

    <div class="box">
        <header class="box__header">
            <h2 class="box__header-title">Upcoming Articles</h2>
        </header>
        <main class="box__body box__body--flush">
            <table class="table">
                <thead class="table__header">
                <tr class="table__row">
                    <th class="table__cell">Name</th>
                    <th class="table__cell">Status</th>
                </tr>
                </thead>
                <tbody class="table__body">
                @foreach ($articles as $article)
                    <tr class="table__row">
                        <td class="table__cell">
                            <a href="{{ route('admin:article.edit', $article->id) }}">
                                {{ $article->name }}
                            </a>
                        </td>
                        <td class="table__cell table__cell--center text--secondary">
                            @if ($article->status === \Ollieread\Core\Support\Status::DRAFT)
                                DRAFT
                            @elseif ($article->status === \Ollieread\Core\Support\Status::REVIEWING)
                                REVIEWING
                            @elseif ($article->status === \Ollieread\Core\Support\Status::PUBLIC)
                                PUBLIC
                            @elseif ($article->status === \Ollieread\Core\Support\Status::PRIVATE)
                                PRIVATE
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </main>
    </div>

    {{--<div class="box">
        <header class="box__header">
            <h3 class="box__header-title">Referrers (30 days)</h3>
        </header>
        <main class="box__body box__body--flush">
            <table class="table">
                <thead class="table__header">
                <tr class="table__row">
                    <th class="table__cell">Referrer</th>
                    <th class="table__cell">Hits</th>
                </tr>
                </thead>
                <tbody class="table__body">
                @foreach ($analytics['referrers'] as $referrer => $hits)
                    <tr class="table__row">
                        <td class="table__cell">
                            {{ $referrer }}
                        </td>
                        <td class="table__cell text--secondary">
                            {{ $hits }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </main>
    </div>

    <div class="box">
        <header class="box__header">
            <h3 class="box__header-title">Page Hits (30 days)</h3>
        </header>
        <main class="box__body box__body--flush">
            <table class="table">
                <thead class="table__header">
                <tr class="table__row">
                    <th class="table__cell">Page</th>
                    <th class="table__cell">Hits</th>
                </tr>
                </thead>
                <tbody class="table__body">
                @foreach ($analytics['pages'] as $page => $hits)
                    <tr class="table__row">
                        <td class="table__cell">
                            {{ $page }}
                        </td>
                        <td class="table__cell text--secondary">
                            {{ $hits }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </main>
    </div>--}}

@endsection
