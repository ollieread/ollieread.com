    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-19364030-1"></script>
    <script>
        if (! window.location.pathname.startsWith('/admin')) {
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', 'UA-19364030-1');

            (function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                              'gtm.start':
                                  new Date().getTime(), event: 'gtm.js',
                          });
                var f  = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', 'GTM-KT25WF5');
        }
    </script>

    <link rel="alternate" type="application/rss+xml" title="ollieread.com" href="/feed.rss">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Ollie Read">
    <meta name="description" content="@yield('page.meta.description', 'Birmingham based PHP and Laravel development')">
    <meta name="keywords" content="@yield('page.meta.keywords')">

    <link rel="canonical" href="@yield('page.meta.canonical', request()->fullUrl())"/>
    <link rel="dns-prefetch" href="//s.w.org"/>
    <link rel="shortcut icon" href="{{ asset('images/small-me-icon.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('images/small-me-icon.png') }}" type="image/png">

    <meta property="og:locale" content="en_GB"/>
    <meta property="og:type" content="@yield('page.type', 'site')"/>
    <meta property="og:title" content="@yield('page.title', 'PHP & Laravel development') - Ollie Read"/>
    <meta property="og:description"
          content="@yield('page.meta.description', 'Birmingham based PHP and Laravel development')"/>
    <meta property="og:image" content="{{ asset('images/small-me-icon.png') }}">
    <meta property="og:url" content="{{ request()->fullUrl() }}"/>
    <meta property="og:site_name" content="Ollie Read"/>

    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:description"
          content="@yield('page.meta.description', 'Birmingham based PHP and Laravel development')"/>
    <meta name="twitter:title" content="@yield('page.title', 'PHP & Laravel development') - Ollie Read"/>
    <meta name="twitter:site" content="@ollieread"/>
    <meta name="twitter:creator" content="@ollieread"/>
    <meta name="twitter:domain" content="https://ollieread.com"/>
    <meta name="twitter:image" content="{{ asset('images/small-me-icon.png') }}">

