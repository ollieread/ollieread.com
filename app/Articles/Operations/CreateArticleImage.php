<?php

namespace Ollieread\Articles\Operations;

use Illuminate\Container\Container;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManagerStatic;

class CreateArticleImage
{
    /**
     * @var \Illuminate\Http\UploadedFile
     */
    private $upload;

    /**
     * @param \Illuminate\Http\UploadedFile $upload
     *
     * @return $this
     */
    public function setUpload(UploadedFile $upload): self
    {
        $this->upload = $upload;

        return $this;
    }

    public function perform()
    {
        $storage  = $this->getStorage();
        $filename = sha1_file($this->upload->getPathname()) . '.png';
        $image    = ImageManagerStatic::make($this->upload->get());

        // Store the original
        $storage->put(
            'images/' . $filename,
            $image->encode('png', 100)->getEncoded()
        );

        // Create & store the article banner
        $storage->put(
            'images/banner_2x_' . $filename,
            $image->fit(1984, 600)->encode('png', 100)->getEncoded()
        );

        // Create & store the article banner
        $storage->put(
            'images/banner_' . $filename,
            $image->fit(992, 300)->encode('png', 100)->getEncoded()
        );

        // Create & store the article thumbnail
        $storage->put(
            'images/thumb_' . $filename,
            $image->fit(300, 300)->encode('png', 100)->getEncoded()
        );

        $image->destroy();

        return $filename;
    }

    private function getStorage(): Filesystem
    {
        return Container::getInstance()->make(FilesystemManager::class)->disk('public');
    }
}
