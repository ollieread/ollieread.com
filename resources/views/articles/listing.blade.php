@extends('layouts.main')

@section('breadcrumbs')
        <a href="{{ route('site:home') }}" class="breadcrumb">Home</a>
        <span class="breadcrumb breadcrumb--active">Articles</span>
@endsection

@section('content')

    @include('articles.partials.articles')

@endsection

@section('sidebar')

    @include('articles.partials.sidebar')

@endsection