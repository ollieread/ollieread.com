@if (isset($categories) && $categories->count())

    <nav class="nav nav--sidebar">

        <h5 class="nav__heading">Categories</h5>

        @foreach($categories as $category)
            <a href="{{ route('articles:category', $category->slug) }}"
               class="nav__link {{ substr_count(request()->getUri(), route('articles:category', $category->slug)) === 1 ? 'nav__link--active' : '' }}">
                <i class="nav__icon fa-{{ $category->icon }}"></i> {{ $category->name }}
            </a>
        @endforeach

    </nav>

@endif