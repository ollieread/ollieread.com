@extends('admin.layout')

@section('breadcrumbs')
    <a href="{{ route('admin:dashboard') }}" class="breadcrumb">Admin</a>
    <a href="{{ route('admin:redirect.index') }}" class="breadcrumb">Redirects</a>
    <span class="breadcrumb breadcrumb--active">Delete Redirect</span>
@endsection

@section('content')

    <header class="page__header">
        <h2 class="page__header-heading">Delete {{ $redirect->name }}</h2>
    </header>

    <form action="{{ route('admin:redirect.destroy', $redirect->id) }}" method="post" class="box box--headerless">
        @csrf

        <main class="box__body">

            <div class="notice notice--warning">
                <p>This is a permanent action, it cannot be reversed</p>
            </div>

            <p>Deleting a redirect will remove it from all other content. Are you sure you want to delete this redirect?</p>

        </main>

        <footer class="box__footer box__footer--secondary">
            <button type="submit" class="button button--primary">Yes</button>
            <a href="{{ route('admin:redirect.index') }}" class="button button--text">No</a>
        </footer>

    </form>

@endsection
