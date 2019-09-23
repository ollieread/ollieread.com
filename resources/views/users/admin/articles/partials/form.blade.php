<div class="input {{ $errors->has('name') ? 'input--invalid' : '' }}">
    <label for="article-name" class="input__label">Name</label>
    <input type="text" name="name" id="article-name" class="input__field"
           placeholder="The article name" value="{{ old('name', (isset($article) ? $article->name : null)) }}">
    {!! $errors->first('name', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('title') ? 'input--invalid' : '' }}">
    <label for="article-title" class="input__label">Title</label>
    <input type="text" name="title" id="article-title" class="input__field"
           placeholder="The article title" value="{{ old('title', (isset($article) ? $article->title : null)) }}">
    {!! $errors->first('title', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('heading') ? 'input--invalid' : '' }}">
    <label for="article-heading" class="input__label">Heading</label>
    <input type="text" name="heading" id="article-heading" class="input__field"
           placeholder="The article heading" value="{{ old('heading', (isset($article) ? $article->heading : null)) }}">
    {!! $errors->first('heading', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('slug') ? 'input--invalid' : '' }}">
    <label for="article-slug" class="input__label">Slug</label>
    <input type="text" name="slug" id="article-slug" class="input__field"
           placeholder="The article slug" value="{{ old('slug', (isset($article) ? $article->slug : null)) }}">
    {!! $errors->first('slug', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('seo_description') ? 'input--invalid' : '' }}">
    <label for="article-seo_description" class="input__label">SEO Description</label>
    <textarea name="seo_description" id="article-seo_description" class="input__field" rows="8"
              placeholder="The article SEO description">{{ old('seo_description', (isset($article) ? $article->seo_description : null)) }}</textarea>
    {!! $errors->first('seo_description', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('excerpt') ? 'input--invalid' : '' }}">
    <label for="article-excerpt" class="input__label">Excerpt</label>
    <textarea name="excerpt" id="article-excerpt" class="input__field" rows="8"
              placeholder="The article excerpt">{{ old('excerpt', (isset($article) ? $article->excerpt : null)) }}</textarea>
    {!! $errors->first('excerpt', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('content') ? 'input--invalid' : '' }}">
    <label for="article-content" class="input__label">Content</label>
    <textarea name="content" id="article-content" data-provides="markdown"
              placeholder="The article content">{{ old('content', (isset($article) ? $article->content : null)) }}</textarea>
    {!! $errors->first('content', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('versions') ? 'input--invalid' : '' }}">
    <label for="article-versions" class="input__label input__label--required">Versions</label>
    <select name="versions[]" id="article-versions" required class="input__field" multiple data-provides="choices">
        @foreach ($versions as $version)
            <option value="{{ $version->id }}" {{ isset($articleVersions) ? ($articleVersions->contains($version->id) ? 'selected' : '') : '' }}>
                {{ $version->name }}
            </option>
        @endforeach
    </select>
    {!! $errors->first('versions', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('topics') ? 'input--invalid' : '' }}">
    <label for="article-topics" class="input__label input__label--required">Topics</label>
    <select name="topics[]" id="article-topics" required class="input__field" multiple data-provides="choices">
        @foreach ($topics as $topic)
            <option value="{{ $topic->id }}" {{ isset($articleTopics) ? ($articleTopics->contains($topic->id) ? 'selected' : '') : '' }}>
                {{ $topic->name }}
            </option>
        @endforeach
    </select>
    {!! $errors->first('topics', '<div class="input__feedback">:message</div>') !!}
</div>
