@if ($social)
    <div class="box box--footerless box--social box--{{ $provider }}">
        <header class="box__header">
            <a href="{{ route('users:account.social.destroy', [$provider, 'redirect_to' => route('users:account.social.edit')]) }}" class="box__header-controls button button--themed-{{ $provider }}">
                Unlink
            </a>
            <div class="box__header-icon"></div>
            <h2 class="box__header-title">{{ $social->username }}</h2>
            <h3 class="box__header-subtitle">{{ $social->social_id }}</h3>
        </header>
        <main class="box__body">
            <div class="avatar avatar--spaced">
                <img src="{{ $social->avatar }}" alt="" class="avatar__image">
            </div>

            <div class="input input--inline {{ $provider !== 'google' ? 'input--light' : '' }}">
                <label for="use-avatar-{{ $provider }}" class="input__label">
                    Use this account as my avatar
                    <input type="radio" id="use-avatar-{{ $provider }}" class="input__field input__field--checkbox"
                           name="use_avatar" value="{{ $provider }}" {{ $social->use_avatar ? 'checked' : '' }}>
                </label>
            </div>
        </main>
    </div>
@else
    <div class="box box--bodyless box--social box--{{ $provider }}">
        <header class="box__header">
            <div class="box__header-icon"></div>
            <a href="{{ route('users:social.auth', [$provider, 'redirect_to' => route('users:account.social.edit')]) }}"
               class="box__header-controls button button--themed-{{ $provider }}">
                Link
            </a>
        </header>
    </div>
@endif
