<?php

namespace Ollieread\Articles\Operations;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Ollieread\Core\Models\Tag;

class GetOrCreateTagsByName
{
    /**
     * @var array
     */
    private $names;

    public function perform(): Collection
    {
        $existing = Tag::query()->whereIn('name', $this->names)->get()->keyBy('name');

        return collect($this->names)->filter(static function (string $name) use ($existing) {
            return $existing->get($name) === null;
        })->map(static function (string $name) {
            return (new Tag)->create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        })->merge($existing->values())->unique('id');
    }

    /**
     * @param array $names
     *
     * @return $this
     */
    public function setNames(array $names): self
    {
        $this->names = $names;
        return $this;
    }
}