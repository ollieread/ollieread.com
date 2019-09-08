<?php

namespace Ollieread\Core\Composers;

use Illuminate\View\View;
use Ollieread\Core\Services\Honeypot;

class HoneypotComposer
{
    /**
     * @var \Ollieread\Core\Services\Honeypot
     */
    private $honeypot;

    public function __construct(Honeypot $honeypot)
    {
        $this->honeypot = $honeypot;
    }

    public function compose(View $view)
    {
        $fields = $this->honeypot->getFieldNames();
        $view->with([
            'honeypot'      => $fields['honeypot'],
            'time'          => $fields['time'],
            'encryptedTime' => $this->honeypot->getTime(),
        ]);
    }
}
