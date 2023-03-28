<?php

namespace App\Services\Employees;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * Table name
     */
    protected $table = 'employee_db';

    protected $appends = [
        'full_name',
        'picture'
    ];

    public $timestamps = false;

    /**
     * Get fullname attribute
     *
     * @return attribute
     */
    public function getFullNameAttribute()
    {
        return trim($this->lastname . ", " . $this->firstname . " " . $this->middlename . " " . $this->ext_name);
    }

    public function getPictureAttribute()
    {
        return $this->img_src == null ? '/assets/img/default-picture.png' : $this->img_src;
    }
}
