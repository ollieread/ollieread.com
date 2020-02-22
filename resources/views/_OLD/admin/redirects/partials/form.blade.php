<div class="input {{ $errors->has('from') ? 'input--invalid' : '' }}">
    <label for="redirect-from" class="input__label">From</label>
    <input type="text" name="from" id="redirect-from" class="input__field"
           placeholder="The URI to redirect from" value="{{ old('from', (isset($redirect) ? $redirect->from : null)) }}">
    {!! $errors->first('from', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('to') ? 'input--invalid' : '' }}">
    <label for="redirect-to" class="input__label">To</label>
    <input type="text" name="to" id="redirect-to" class="input__field"
           placeholder="The URI to redirect to" value="{{ old('to', (isset($redirect) ? $redirect->to : null)) }}">
    {!! $errors->first('to', '<div class="input__feedback">:message</div>') !!}
</div>
