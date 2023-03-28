<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\GradeLevel;
use App\Libraries\Branch;

class Product extends Model
{
    protected $fillable = [
      'productname',
      'package',
      'category',
      'image'
    ];

    // protected $appends = [
    //   'grade_name',
    // ];

    // public function getGradeNameAttribute()
    // {
    //   $branch = Branch::get($this->branch_code, false, false, true);
    //   $gradeName = GradeLevel::on($branch[0])->where('gradelevel', $this->gradelevel)->select('grade_name')->first();
    //     if(!empty($gradeName)){
    //       return $gradeName->grade_name;
    //     } else {
    //       return 'N/A';
    //     }
  
    // }
}
