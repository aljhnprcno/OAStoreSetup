<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
    protected $fillable = ['type', 'display_name', 'image_path'];
}
