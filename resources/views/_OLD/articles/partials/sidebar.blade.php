<nav class="nav nav--sidebar">

    <a href="{{ route('site:home') }}"
       class="nav__link {{ request()->routeIs('site:home') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-home"></i> Home
    </a>

    <a href="{{ route('articles:index') }}"
       class="nav__link {{ request()->routeIs('articles:index') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-newspaper"></i> Articles
    </a>

    <a href="{{ route('articles:upcoming') }}"
       class="nav__link {{ request()->routeIs('articles:upcoming') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-calendar-alt"></i> Upcoming
    </a>

    <a href="{{ route('articles:versions') }}"
       class="nav__link {{ request()->routeIs('articles:versions') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-laptop-code"></i> Versions
    </a>

    <a href="{{ route('articles:topics') }}"
       class="nav__link {{ request()->routeIs('articles:topics') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-file-certificate"></i> Topics
    </a>

    {{--<a href="/" class="nav__link">
        <i class="nav__icon fa-chalkboard-teacher"></i> Courses
    </a>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-box"></i> Packages
    </a>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-code"></i> Services
    </a>--}}

</nav>

@include('articles.partials.sidebar.categories')

@include('articles.partials.sidebar.series')
