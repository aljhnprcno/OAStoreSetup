<?php

namespace App\Services\Pictures\Traits;

use App\Services\Pictures\Picture;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;

trait UploadableModelPictureTrait
{
    public function getDefaultFullPath(): string
    {
        return '/assets/img/md-16x9.png';
    }

    public function getDefaultThumbnailPath(): string
    {
        return '/assets/img/md-1x1.png';
    }

    public function picture(): BelongsTo
    {
        return $this->belongsTo(Picture::class)->withDefault([
            'full_path' => $this->getDefaultFullPath(),
            'thumbnail_path' => $this->getDefaultThumbnailPath()
        ]);
    }

    public function setPicture(UploadedFile $file)
    {
        /** @var Picture $picture */
        $picture = $this->picture ?? new Picture();

        $picture->upload($file, $this->getUploadNamespace(),
            $this->getPictureFullSize(), $this->getPictureThumbnailSize());

        $picture->save();

        if ($this->picture_id != $picture->id) {
            $this->picture_id = $picture->id;

            $this->save();
        }
    }
}
