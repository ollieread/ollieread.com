<?php

namespace Ollieread\Users\Actions\Admin\Versions;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ollieread\Articles\Operations\CreateVersion;
use Ollieread\Core\Support\Action;
use Ollieread\Core\Support\Alerts;

class Create extends Action
{
    public function create()
    {
        return $this->response()->view('users.admin.versions.create');
    }

    public function store(Request $request)
    {
        $input = $request->only([
            'name',
            'slug',
            'description',
            'docs',
            'release_date',
        ]);

        if (empty($input['slug'])) {
            $input['slug'] = Str::slug($input['name']);
        }

        if ((new CreateVersion)->setInput($input)->perform()) {
            Alerts::success(trans('users.admin.create.success', ['entity' => trans_choice('entities.version', 1)]), 'admin.versions');

            return $this->response()->redirectToRoute('admin:version.index');
        }

        Alerts::error(trans('error.unexpected'), 'admin.versions');

        return $this->response()->redirectToRoute('admin:version.create');
    }
}
