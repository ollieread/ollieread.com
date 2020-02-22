@if (isset($series) && $series->count())

    <nav class="nav nav--sidebar">

        <h5 class="nav__heading">Series</h5>

        <a href="/" class="nav__link">
            <i class="nav__icon fa-books"></i> All
        </a>

        @foreach($series as $item)
            <a href="{{ route('articles:series', $item->slug) }}" class="nav__link">
                <i class="nav__icon fa-book"></i> {{ $item->name }}
            </a>
        @endforeach

    </nav>

@endif