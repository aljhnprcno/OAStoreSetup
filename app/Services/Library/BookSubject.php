<?php

namespace App\Services\Library;

use Illuminate\Database\Eloquent\Model;

class BookSubject extends Model
{
    protected $fillable = ['description'];

    public function books() {
        return $this->hasMany(Book::class, 'book_subject_id', 'id');
    }

}
