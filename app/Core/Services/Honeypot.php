<?php

namespace Ollieread\Core\Services;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Str;

class Honeypot
{
    /**
     * @var \Illuminate\Session\Store
     */
    private $session;

    /**
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    private $encrypter;

    public function __construct(Store $session, Encrypter $encrypter)
    {
        $this->session   = $session;
        $this->encrypter = $encrypter;
    }

    public function getFieldNames(): array
    {
        $fields = [
            'honeypot' => 'nickname_' . Str::random(16),
            'time'     => 'eleven_' . Str::random(16),
        ];

        $this->session->put('honeypot', $fields);

        return $fields;
    }

    public function getTime(): string
    {
        return $this->encrypter->encrypt(time());
    }

    public function validate(Request $request): bool
    {
        $fields = $this->session->pull('honeypot', []);

        if (! $fields) {
            return false;
        }

        if (isset($fields['honeypot']) && (! $request->has($fields['honeypot']) || ! empty($request->input($fields['honeypot'])))) {
            return false;
        }

        if (isset($fields['time'])) {
            if (! $request->has($fields['time'])) {
                return false;
            }

            $time = $this->encrypter->decrypt($request->input($fields['time']));

            if (! is_numeric($time)) {
                return false;
            }

            if ($time > time()) {
                return false;
            }

            if ($time < time() + 3) {
                return false;
            }

            if ($time < 0) {
                return false;
            }

            if ($time >= PHP_INT_MAX) {
                return false;
            }
        }

        return true;
    }
}
