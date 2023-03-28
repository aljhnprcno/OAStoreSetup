<?php

namespace App\Services\Pictures;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Picture extends Model
{
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->{$model->getKeyName()} = Uuid::uuid1()->toString();
        });
    }

    /**
     * Get full_path with storage prefix url
     * @return string
     */
    public function getFullPathAttribute(): string
    {
        if (strpos($this->attributes['full_path'], 'https://') !== false) {
            return $this->attributes['full_path'];
        }

        if (array_key_exists('id', $this->attributes)) {
            return $this->storage()->url($this->attributes['full_path']);
        }

        return $this->attributes['full_path'];
    }

    /**
     * Get thumbnail_path with storage prefix url
     * @return string
     */
    public function getThumbnailPathAttribute(): string
    {
        if (strpos($this->attributes['thumbnail_path'], 'https://') !== false) {
            return $this->attributes['thumbnail_path'];
        }

        if (array_key_exists('id', $this->attributes)) {
            return $this->storage()->url($this->attributes['thumbnail_path']);
        }

        return $this->attributes['thumbnail_path'];
    }

    /**
     * @param UploadedFile $file
     * @param string $namespace
     * @param int[] $fullSize
     * @param int[] $thumbnailSize
     */
    public function upload(UploadedFile $file, string $namespace, array $fullSize, array $thumbnailSize)
    {
        $processor = new PictureProcessor($file);
        $full = $processor->resize(...$fullSize)->process(85);
        $fullName = $this->generateFileName($namespace, $fullSize, $processor->getFormat());

        $thumbnail = $processor->thumbnail(...$thumbnailSize)->process();
        $thumbnailName = $this->generateFileName($namespace, $thumbnailSize, $processor->getFormat());

        $this->full_path = $fullName;
        $this->thumbnail_path = $thumbnailName;
        $this->original_filename = $file->getClientOriginalName();

        $this->storage()->put($fullName, $full);
        $this->storage()->put($thumbnailName, $thumbnail);

        $this->save();
    }

    /**
     * Generate target filename
     *
     * @param string $namespace
     * @param int[] $size
     * @param string $extension
     * @return string
     */
    protected function generateFileName(string $namespace, array $size, string $extension): string
    {
        $randomId = uniqid();
        $sizeString = implode('x', $size);

        $fileName = sprintf('/%s/%s/%s/%s_%s_%s.%s',
            $namespace, date('Y'), date('m'), time(), $randomId, $sizeString, $extension
        );

        return $fileName;
    }

    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\FilesystemAdapter
     */
    protected function storage()
    {
        return Storage::disk();
    }
}
