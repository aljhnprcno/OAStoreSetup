<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\GradeLevel;
use App\Libraries\Branch;

class Package extends Model
{
  protected $fillable = [
    'package_name',
    'grade_level_id',
    'branch_code',
    'package',
    'category'
  ];

  protected $appends = [
    'grade_name',
  ];

  public function getGradeNameAttribute()
  {
    $branch = Branch::get($this->branch_code, false, false, true);
    $gradeName = GradeLevel::on($branch[0])->where('grade_level_id', $this->grade_level_id)->select('grade_name')->first();
      if(!empty($gradeName)){
        return $gradeName->grade_name;
      }
  }
}
