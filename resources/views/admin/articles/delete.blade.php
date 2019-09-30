@extends('admin.layout')

@section('breadcrumbs')
    <a href="{{ route('admin:dashboard') }}" class="breadcrumb">Admin</a>
    <a href="{{ route('admin:article.index') }}" class="breadcrumb">Articles</a>
    <span class="breadcrumb breadcrumb--active">Delete Article</span>
@endsection

@section('content')

    <header class="page__header">
        <h2 class="page__header-heading">Delete {{ $article->name }}</h2>
    </header>

    <form action="{{ route('admin:article.destroy', $article->id) }}" method="post" class="box box--headerless">
        @csrf

        <main class="box__body">

            <div class="notice notice--warning">
                <p>This is a permanent action, it cannot be reversed</p>
            </div>

            <p>Deleting a article will remove it from all other content. Are you sure you want to delete this article?</p>

            <div class="input {{ $errors->has('password') ? 'input--invalid' : '' }}">
                <label for="article-password" class="input__label">Enter your password</label>
                <input type="password" name="password" id="article-password" class="input__field"
                       placeholder="Your current password" autocomplete="off">
                {!! $errors->first('password', '<div class="input__feedback">:message</div>') !!}
            </div>

        </main>

        <footer class="box__footer box__footer--secondary">
            <button type="submit" class="button button--primary">Yes</button>
            <a href="{{ route('admin:article.index') }}" class="button button--text">No</a>
        </footer>

    </form>

@endsection
