@extends('layouts.main')

@section('header')
    @include('partials.main-header')
@endsection

@section('content')
    @include('partials.home-sections.what-i-do')
    @include('partials.home-sections.what-i-use')
@endsection