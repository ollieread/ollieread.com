<?php

namespace Ollieread\Admin\Actions\Redirects;

use Illuminate\Http\Request;
use Ollieread\Core\Operations\CreateRedirect;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;

class Create extends Action
{
    public function create()
    {
        return $this->response()->view('admin.redirects.create');
    }

    public function store(Request $request)
    {
        $input = $request->only([
            'from',
            'to',
        ]);

        if ((new CreateRedirect)->setInput($input)->perform()) {
            Alerts::success(trans('admin.create.success', ['entity' => trans_choice('entities.redirect', 1)]), 'admin.redirects');

            return $this->response()->redirectToRoute('admin:redirect.index');
        }

        Alerts::error(trans('error.unexpected'), 'admin.redirects');

        return $this->response()->redirectToRoute('admin:redirect.create');
    }
}
