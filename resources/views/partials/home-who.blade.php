<section class="panel panel--primary">
    <header class="panel__title">Who I Am</header>
    <main class="panel__body panel__body--with-img">
        <div class="panel__body-image">
            <img src="{{ asset('/images/picture-of-me.jpg') }}" class="image image--rounded" alt="">
        </div>
        <div class="panel__body-text">
            <p>
                I’m a 31 year father of two living in Birmingham, England.
            </p>

            <p>
                I write <a href="{{ route('articles:index') }}">articles</a>
            </p>
        </div>
    </main>
</section>