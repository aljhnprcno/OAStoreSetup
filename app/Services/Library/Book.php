<?php

namespace App\Services\Library;

use App\Services\Pictures\Traits\UploadableModelPictureTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;

class Book extends Model
{
    protected $guarded = [];
    use UploadableModelPictureTrait;


    public function getUploadNamespace(): string
    {
        return 'books';
    }

    public function getPictureFullSize(): array
    {
        return [1080];
    }

    public function getPictureThumbnailSize(): array
    {
        return [256, 256];
    }

    public function getDefaultFullPath(): string
    {
        return env('FOLDER_NAME') . '/public/assets/img/book_cover.png';
    }

    public function getDefaultThumbnailPath(): string
    {
        return env('FOLDER_NAME') . '/public/assets/img/book_cover.png';
    }


    public function book_category(): BelongsTo
    {
        return $this->belongsTo(BookCategory::class);
    }

    public function book_series(): BelongsTo
    {
        return $this->belongsTo(BookSeries::class);
    }

    public function book_requests(): HasMany
    {
        return $this->hasMany(BookRequest::class, 'book_id', 'id');
    }

    public function book_subject() {
        return $this->belongsTo(BookSubject::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($book) {
            $book->slug = Str::slug($book->title) . '-' .Uuid::uuid1()->toString();
        });
    }

}
