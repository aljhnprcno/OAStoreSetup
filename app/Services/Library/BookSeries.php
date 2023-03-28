<?php

namespace App\Services\Library;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookSeries extends Model
{
    protected $guarded = [];
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'book_series_id', 'id');
    }
}
