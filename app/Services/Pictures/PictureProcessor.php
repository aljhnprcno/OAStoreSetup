<?php

namespace App\Services\Pictures;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

class PictureProcessor
{
    const FORMAT_PNG = 'png';

    const FORMAL_JPEG = 'jpg';

    /**
     * @var File
     */
    protected $file;

    /**
     * @var \Intervention\Image\Image
     */
    protected $image;

    /**
     * Default output format
     *
     * @var string
     */
    protected $format;

    /**
     * Default quality
     *
     * @var string
     */
    protected $quality;

    /**
     * PictureProcessor constructor.
     *
     * @param UploadedFile $file
     * @param string $format
     * @param int $quality
     */
    public function __construct(UploadedFile $file, $format = self::FORMAL_JPEG, $quality = 100)
    {
        $this->file = $file;

        $this->image = Image::make($file);

        $this->format = $format;

        $this->quality = $quality;
    }

    /**
     * @param int $width
     * @param int|null $height
     * @return $this
     */
    public function resize(int $width, ?int $height = null)
    {
        $processor = clone $this;

        if ($height) {
            $processor->image = $processor->image->fit($width, $height);

            return $processor;
        }

        $processor->image = $processor->image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $processor;
    }

    /**
     * @param int $size
     * @return $this
     */
    public function thumbnail(int $size)
    {
        $processor = clone $this;

        $processor->image = $processor->image->fit($size);

        return $processor;
    }

    /**
     * @param null $quality
     * @param null $format
     * @return \Psr\Http\Message\StreamInterface
     */
    public function process($quality = null, $format = null)
    {
        $format = $format ?? $this->format;
        $quality = $quality ?? $this->quality;

        return $this->image->stream($format, $quality);
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }
}
