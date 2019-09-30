<div class="input {{ $errors->has('name') ? 'input--invalid' : '' }}">
    <label for="user-name" class="input__label">Name</label>
    <input type="text" name="name" id="user-username" class="input__field"
           placeholder="Enter your name" value="{{ old('name', $user->name) }}">
    {!! $errors->first('name', '<div class="input__feedback">:message</div>') !!}
    <div class="input__info">
        Their real name
    </div>
</div>

<div class="input {{ $errors->has('username') ? 'input--invalid' : '' }}">
    <label for="user-username" class="input__label input__label--required">Username</label>
    <input type="text" id="user-username" required class="input__field" name="username"
           placeholder="Enter your username" value="{{ old('username', $user->username) }}">
    {!! $errors->first('username', '<div class="input__feedback">:message</div>') !!}
</div>

<div class="input {{ $errors->has('email') ? 'input--invalid' : '' }}">
    <label for="user-email" class="input__label input__label--required">Email</label>
    <input type="email" name="email" id="user-email" required class="input__field"
           placeholder="Enter your email address" value="{{ old('email', $user->email) }}">
    {!! $errors->first('email', '<div class="input__feedback">:message</div>') !!}
    <div class="input__info">
        For verification and occasional contact
    </div>
</div>

<div class="input {{ $errors->has('roles') ? 'input--invalid' : '' }}">
    <label for="user-roles" class="input__label input__label--required">Roles</label>
    <select name="roles[]" id="user-roles" required class="input__field" multiple data-provides="choices">
        @foreach ($roles as $role)
            <option value="{{ $role->id }}" {{ $userRoles->contains($role->id) ? 'selected' : '' }}>{{ $role->name }}
            </option>
        @endforeach
    </select>
    {!! $errors->first('roles', '<div class="input__feedback">:message</div>') !!}
</div>
