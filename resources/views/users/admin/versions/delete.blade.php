@extends('users.admin.layout')

@section('breadcrumbs')
    <a href="{{ route('admin:dashboard') }}" class="breadcrumb">Admin</a>
    <a href="{{ route('admin:version.index') }}" class="breadcrumb">Versions</a>
    <span class="breadcrumb breadcrumb--active">Delete Version</span>
@endsection

@section('content')

    <header class="page__header">
        <h2 class="page__header-heading">Delete {{ $version->name }}</h2>
    </header>

    <form action="{{ route('admin:version.destroy', $version->id) }}" method="post" class="box box--headerless">
        @csrf

        <main class="box__body">

            <div class="notice notice--warning">
                <p>This is a permanent action, it cannot be reversed</p>
            </div>

            <p>Deleting a version will remove it from all other content. Are you sure you want to delete this version?</p>

            <div class="input {{ $errors->has('password') ? 'input--invalid' : '' }}">
                <label for="version-password" class="input__label">Enter your password</label>
                <input type="password" name="password" id="version-password" class="input__field"
                       placeholder="Your current password" autocomplete="off">
                {!! $errors->first('password', '<div class="input__feedback">:message</div>') !!}
            </div>

        </main>

        <footer class="box__footer box__footer--secondary">
            <button type="submit" class="button button--primary">Yes</button>
            <a href="{{ route('admin:version.index') }}" class="button button--text">No</a>
        </footer>

    </form>

@endsection
