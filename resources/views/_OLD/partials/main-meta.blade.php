    <script type="application/ld+json">
        {
          "@context": "http://schema.org",
          "@type": "Person",
          "email": "mailto:me@ollieread.com",
          "jobTitle": "Freelance Laravel & PHP Developer",
          "name": "Ollie Read",
          "gender": "male",
          "nationality": "White British",
          "url": "{{ env('APP_URL', 'https://ollieread.com') }}",
          "sameAs" : [
              "https://www.linkedin.com/in/ollieread/",
              "https://twitter.com/ollieread",
              "https://www.instagram.com/ollieread/",
              "https://github.com/ollieread",
              "https://stackoverflow.com/users/3104359/ollieread"
            ]
        }
    </script>

    <script type="application/ld+json">
        {
          "@context": "http://schema.org",
          "@type": "WebSite",
          "url": "{{ env('APP_URL', 'https://ollieread.com') }}",
          "name": "@yield('page.title', 'PHP & Laravel development') - Ollie Read",
           "author": {
              "@type": "Person",
              "name": "Ollie Read"
            },
          "description": "@yield('page.meta.description', 'Birmingham based PHP and Laravel development')",
          "publisher": {
            "@type": "Organization",
            "name": "ollieread.com",
            "logo": {
              "@type": "ImageObject",
              "url": "{{ asset('images/small-me-icon.png') }}"
            }
          }
        }
    </script>
