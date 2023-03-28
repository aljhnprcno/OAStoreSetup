<?php

namespace App\Services\Research;

use App\Services\LearningMaterials\SubjectArea;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;

class Research extends Model
{
    protected $table = 'researches';
    
    protected $fillable = [
        'entity_id',
        'title',
        'department',
        'research_code',
        'publisher',
        'keywords',
        'journal_name',
        'year_published',
        'cover_image',
        'branch_code',
        'author_ids'
    ];

    public function research_files() {
        return $this->hasMany(ResearchFile::class);
    }

    public function subject_area()
    {
        return $this->belongsTo(SubjectArea::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($research) {
            $research->slug = Str::slug($research->research_code) . '-' .Uuid::uuid1()->toString();
        });
    }
}
