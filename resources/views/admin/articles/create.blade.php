@extends('admin.layout')

@section('breadcrumbs')
    <a href="{{ route('admin:dashboard') }}" class="breadcrumb">Admin</a>
    <a href="{{ route('admin:article.index') }}" class="breadcrumb">Articles</a>
    <span class="breadcrumb breadcrumb--active">Create Article</span>
@endsection

@section('content')

    <header class="page__header">
        <h2 class="page__header-heading">Create article</h2>
    </header>

    <form action="{{ route('admin:article.store') }}" method="post" class="box box--headerless">
        @csrf

        <main class="box__body">

            @include('admin.articles.partials.form')

        </main>

        <footer class="box__footer box__footer--secondary">
            <button type="submit" class="button button--primary">Create</button>
            <a href="{{ route('admin:article.index') }}" class="button button--text">Cancel</a>
        </footer>

    </form>

@endsection
