@extends('admin.layout')

@section('breadcrumbs')
    <a href="{{ route('admin:dashboard') }}" class="breadcrumb">Admin</a>
    <a href="{{ route('admin:user.index') }}" class="breadcrumb">Users</a>
    <span class="breadcrumb breadcrumb--active">Delete User</span>
@endsection

@section('content')

    <header class="page__header">
        <h2 class="page__header-heading">Delete {{ $user->name }}</h2>
    </header>

    <form action="{{ route('admin:user.destroy', $user->id) }}" method="post" class="box box--headerless">
        @csrf

        <main class="box__body">

            <div class="notice notice--warning">
                <p>This is a permanent action, it cannot be reversed</p>
            </div>

            <p>Deleting a user will prevent this user from logging in, and will free up the username and email to be used again. Are you sure you want this?</p>

            <div class="input">
                <label class="input__label">
                    <input type="checkbox" name="cascade" value="mwl" {{ old('cascade') ? 'checked' : '' }}> Cascade
                </label>
                <div class="input__info">Cascade and delete all child entries for this user</div>
            </div>

            <div class="input {{ $errors->has('password') ? 'input--invalid' : '' }}">
                <label for="user-password" class="input__label">Enter your password</label>
                <input type="password" name="password" id="user-password" class="input__field"
                       placeholder="Your current password" autocomplete="off">
                {!! $errors->first('password', '<div class="input__feedback">:message</div>') !!}
            </div>

        </main>

        <footer class="box__footer box__footer--secondary">
            <button type="submit" class="button button--primary">Yes</button>
            <a href="{{ route('admin:user.index') }}" class="button button--text">No</a>
        </footer>

    </form>

@endsection
