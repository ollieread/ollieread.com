@extends('admin.layout')

@section('breadcrumbs')
    <a href="{{ route('admin:dashboard') }}" class="breadcrumb">Admin</a>
    <a href="{{ route('admin:redirect.index') }}" class="breadcrumb">Redirects</a>
    <span class="breadcrumb breadcrumb--active">Edit Redirect</span>
@endsection

@section('content')

    <header class="page__header">
        <h2 class="page__header-heading">Edit {{ $redirect->name }}</h2>
    </header>

    <form action="{{ route('admin:redirect.update', $redirect->id) }}" method="post" class="box box--headerless">
        @csrf

        <main class="box__body">

            @include('admin.redirects.partials.form')

        </main>

        <footer class="box__footer box__footer--secondary">
            <button type="submit" class="button button--primary">Update</button>
            <a href="{{ route('admin:redirect.index') }}" class="button button--text">Cancel</a>
        </footer>

    </form>

@endsection
