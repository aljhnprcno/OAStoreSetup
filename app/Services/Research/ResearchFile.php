<?php

namespace App\Services\Research;

use Illuminate\Database\Eloquent\Model;

class ResearchFile extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'research_id',
        'file_name',
        'file_path',
        'abstract_file_name',
        'abstract_file_path',
        'type',
        'abstract_type',
    ];
    
    public function research() {
        return $this->belongsTo(Research::class);
    }
}
