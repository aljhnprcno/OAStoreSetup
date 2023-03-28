<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $guarded = [];

    public function pickup() {
        return $this->hasMany(Pickup::class, 'request_id', 'id');
    }
}
