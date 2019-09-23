<div class="input {{ $errors->has('name') ? 'input--invalid' : '' }}">
    <label for="version-name" class="input__label">Name</label>
    <input type="text" name="name" id="version-name" class="input__field"
           placeholder="The version name" value="{{ old('name', (isset($version) ? $version->name : null)) }}">
    {!! $errors->first('name', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('slug') ? 'input--invalid' : '' }}">
    <label for="version-slug" class="input__label">Slug</label>
    <input type="text" name="slug" id="version-slug" class="input__field"
           placeholder="The version slug" value="{{ old('slug', (isset($version) ? $version->slug : null)) }}">
    {!! $errors->first('slug', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('description') ? 'input--invalid' : '' }}">
    <label for="version-description" class="input__label">Description</label>
    <textarea name="description" id="version-description" class="input__field" rows="8"
              placeholder="The version description">{{ old('description', (isset($version) ? $version->description : null)) }}</textarea>
    {!! $errors->first('description', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('docs') ? 'input--invalid' : '' }}">
    <label for="version-docs" class="input__label">Docs</label>
    <input type="text" name="docs" id="version-docs" class="input__field"
           placeholder="The version docs" value="{{ old('docs', (isset($version) ? $version->docs : null)) }}">
    {!! $errors->first('docs', '<div class="input__feedback">:message</div>') !!}
    <div class="input__info">
        A link (including scheme) to the documentation
    </div>
</div>

<div class="input {{ $errors->has('release_date') ? 'input--invalid' : '' }}">
    <label for="version-release_date" class="input__label">Release Date</label>
    <input type="date" name="release_date" id="version-release_date" class="input__field"
           placeholder="The version release date" value="{{ old('release_date', (isset($version) ? $version->release_date->format('Y-m-d') : null)) }}">
    {!! $errors->first('release_date', '<div class="input__feedback">:message</div>') !!}
</div>
