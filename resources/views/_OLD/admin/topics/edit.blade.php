@extends('admin.layout')

@section('breadcrumbs')
    <a href="{{ route('admin:dashboard') }}" class="breadcrumb">Admin</a>
    <a href="{{ route('admin:topic.index') }}" class="breadcrumb">Topics</a>
    <span class="breadcrumb breadcrumb--active">Edit Topic</span>
@endsection

@section('content')

    <header class="page__header">
        <h2 class="page__header-heading">Edit {{ $topic->name }}</h2>
    </header>

    <form action="{{ route('admin:topic.update', $topic->id) }}" method="post" class="box box--headerless">
        @csrf

        <main class="box__body">

            @include('admin.topics.partials.form')

        </main>

        <footer class="box__footer box__footer--secondary">
            <button type="submit" class="button button--primary">Update</button>
            <a href="{{ route('admin:topic.index') }}" class="button button--text">Cancel</a>
        </footer>

    </form>

@endsection
