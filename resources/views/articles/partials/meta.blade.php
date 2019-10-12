{!! $article->metadata !!}

<meta property="article:published_time" content="{{ $article->post_at->format('Ymd\THis\Z') }}" />
<meta property="article:modified_time" content="{{ $article->updated_at->format('Ymd\THis\Z') }}" />
<meta property="article:section" content="{{ $article->category->name }}" />
<meta property="article:author:username" content="ollieread" />
<meta property="article:author:first_name" content="Ollie" />
<meta property="article:author:last_name" content="Read" />

@foreach ($article->topics as $topic)
    <meta property="article:tag" content="{{ $topic->name }}">
@endforeach

@foreach ($article->versions as $version)
    <meta property="article:tag" content="{{ $version->name }}">
@endforeach
