<div class="input {{ $errors->has('name') ? 'input--invalid' : '' }}">
    <label for="article-name" class="input__label">Name</label>
    <input type="text" name="name" id="article-name" class="input__field" required
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

<div class="input {{ $errors->has('image') ? 'input--invalid' : '' }}">
    <label for="article-slug" class="input__label">Image</label>
    <input type="file" name="image" id="article-image" class="input__field"
           placeholder="The article image">
    {!! $errors->first('image', '<div class="input__feedback">:message</div>') !!}
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

<div class="input {{ $errors->has('category') ? 'input--invalid' : '' }}">
    <label for="article-category" class="input__label">Category</label>
    <div class="input__field--select">
        <select name="category" id="article-category" required class="input__field">
            <option value="">Select a Category</option>
            @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}" {{ old('category', (isset($article) ? $article->category_id == $category->id : null)) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    {!! $errors->first('category', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('versions') ? 'input--invalid' : '' }}">
    <label for="article-versions" class="input__label">Versions</label>
    <select name="versions[]" id="article-versions" class="input__field" multiple data-provides="choices">
        @foreach ($versions as $version)
            <option
                value="{{ $version->id }}" {{ isset($articleVersions) ? ($articleVersions->contains($version->id) ? 'selected' : '') : '' }}>
                {{ $version->name }}
            </option>
        @endforeach
    </select>
    {!! $errors->first('versions', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('topics') ? 'input--invalid' : '' }}">
    <label for="article-topics" class="input__label">Topics</label>
    <select name="topics[]" id="article-topics" class="input__field" multiple data-provides="choices">
        @foreach ($topics as $topic)
            <option
                value="{{ $topic->id }}" {{ isset($articleTopics) ? ($articleTopics->contains($topic->id) ? 'selected' : '') : '' }}>
                {{ $topic->name }}
            </option>
        @endforeach
    </select>
    {!! $errors->first('topics', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('status') ? 'input--invalid' : '' }}">
    <label for="article-status" class="input__label">Status</label>
    <div class="input__field--select">
        <select name="status" id="article-status" required class="input__field">
            <option
                value="{{ \Ollieread\Core\Support\Status::DRAFT }}" {{ ((int) old('status', isset($article) ? $article->status : 0)) ===  \Ollieread\Core\Support\Status::DRAFT ? 'selected' : ''}}>
                Draft
            </option>
            <option value="{{ \Ollieread\Core\Support\Status::REVIEWING }}" {{ ((int) old('status', isset($article) ? $article->status : 0)) ===  \Ollieread\Core\Support\Status::REVIEWING ? 'selected' : ''}}>Reviewing</option>
            <option value="{{ \Ollieread\Core\Support\Status::PUBLIC }}" {{ ((int) old('status', isset($article) ? $article->status : 0)) ===  \Ollieread\Core\Support\Status::PUBLIC ? 'selected' : ''}}>Public</option>
            <option value="{{ \Ollieread\Core\Support\Status::PRIVATE }}" {{ ((int) old('status', isset($article) ? $article->status : 0)) ===  \Ollieread\Core\Support\Status::PRIVATE ? 'selected' : ''}}>Private</option>
        </select>
    </div>
    {!! $errors->first('status', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('name') ? 'input--invalid' : '' }}">
    <label for="article-post_at" class="input__label">Post At</label>
    <input type="datetime-local" name="post_at" id="article-post_at" class="input__field"
           placeholder="The time and date the article should be released"
           value="{{ old('post_at', (isset($article) && $article->post_at ? $article->post_at->format('Y-m-d\TH:i') : null)) }}">
    {!! $errors->first('post_at', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('active') ? 'input--invalid' : '' }}">
    <label class="input__label">
        <input type="checkbox" name="active"
               value="1" {{ old('active', (isset($article) ? $article->active : false)) ? 'checked' : '' }}> Article is
        active
    </label>
    {!! $errors->first('active', '<div class="input__feedback">:message</div>') !!}
</div>
