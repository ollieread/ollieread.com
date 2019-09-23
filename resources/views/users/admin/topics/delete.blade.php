@extends('users.admin.layout')

@section('breadcrumbs')
    <a href="{{ route('admin:dashboard') }}" class="breadcrumb">Admin</a>
    <a href="{{ route('admin:topic.index') }}" class="breadcrumb">Topics</a>
    <span class="breadcrumb breadcrumb--active">Delete Topic</span>
@endsection

@section('content')

    <header class="page__header">
        <h2 class="page__header-heading">Delete {{ $topic->name }}</h2>
    </header>

    <form action="{{ route('admin:topic.destroy', $topic->id) }}" method="post" class="box box--headerless">
        @csrf

        <main class="box__body">

            <div class="notice notice--warning">
                <p>This is a permanent action, it cannot be reversed</p>
            </div>

            <p>Deleting a topic will remove it from all other content. Are you sure you want to delete this topic?</p>

            <div class="input {{ $errors->has('password') ? 'input--invalid' : '' }}">
                <label for="topic-password" class="input__label">Enter your password</label>
                <input type="password" name="password" id="topic-password" class="input__field"
                       placeholder="Your current password" autocomplete="off">
                {!! $errors->first('password', '<div class="input__feedback">:message</div>') !!}
            </div>

        </main>

        <footer class="box__footer box__footer--secondary">
            <button type="submit" class="button button--primary">Yes</button>
            <a href="{{ route('admin:topic.index') }}" class="button button--text">No</a>
        </footer>

    </form>

@endsection
