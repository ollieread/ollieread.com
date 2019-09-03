@foreach (\Ollieread\Core\Support\Alerts::messages(\Ollieread\Core\Support\Alerts::ERROR, $context ?? 'default') as $alert)
    <div class="notice notice--error">
        {{ $alert }}
    </div>
@endforeach

@foreach (\Ollieread\Core\Support\Alerts::messages(\Ollieread\Core\Support\Alerts::INFO, $context ?? 'default') as $alert)
    <div class="notice notice--info">
        {{ $alert }}
    </div>
@endforeach

@foreach (\Ollieread\Core\Support\Alerts::messages(\Ollieread\Core\Support\Alerts::SUCCESS, $context ?? 'default') as $alert)
    <div class="notice notice--success">
        {{ $alert }}
    </div>
@endforeach

@foreach (\Ollieread\Core\Support\Alerts::messages(\Ollieread\Core\Support\Alerts::WARNING, $context ?? 'default') as $alert)
    <div class="notice notice--warning">
        {{ $alert }}
    </div>
@endforeach
