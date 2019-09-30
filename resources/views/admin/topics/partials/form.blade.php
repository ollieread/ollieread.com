<div class="input {{ $errors->has('name') ? 'input--invalid' : '' }}">
    <label for="topic-name" class="input__label">Name</label>
    <input type="text" name="name" id="topic-name" class="input__field"
           placeholder="The topic name" value="{{ old('name', (isset($topic) ? $topic->name : null)) }}">
    {!! $errors->first('name', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('slug') ? 'input--invalid' : '' }}">
    <label for="topic-slug" class="input__label">Slug</label>
    <input type="text" name="slug" id="topic-slug" class="input__field"
           placeholder="The topic slug" value="{{ old('slug', (isset($topic) ? $topic->slug : null)) }}">
    {!! $errors->first('slug', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('description') ? 'input--invalid' : '' }}">
    <label for="topic-description" class="input__label">Description</label>
    <textarea name="description" id="topic-description" class="input__field" rows="8"
              placeholder="The topic description">{{ old('description', (isset($topic) ? $topic->description : null)) }}</textarea>
    {!! $errors->first('description', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('content') ? 'input--invalid' : '' }}">
    <label for="topic-content" class="input__label">Content</label>
    <textarea name="content" id="topic-content" data-provides="markdown"
              placeholder="The topic content">{{ old('content', (isset($topic) ? $topic->content : null)) }}</textarea>
    {!! $errors->first('content', '<div class="input__feedback">:message</div>') !!}
</div>
