<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\GradeLevel;
use App\Libraries\Branch;

class Category extends Model
{
  protected $fillable = [
    'category_name',
    'store_package_id',
    'has_sizes',
  ];

  // protected $appends = [
  //   'grade_name',
  // ];

  // public function getGradeNameAttribute()
  // {
  //   $branch = Branch::get($this->branch_code, false, false, true);
  //   $gradeName = GradeLevel::on($branch[0])->where('store_package_id', $this->store_package_id)->select('grade_name')->first();
  //     if(!empty($gradeName)){
  //       return $gradeName->grade_name; 
  //     }

  // }
}
