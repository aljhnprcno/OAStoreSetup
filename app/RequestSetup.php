<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestSetup extends Model
{
    protected $fillable = ['branch_code', 'uuid', 'email_address'];
}
