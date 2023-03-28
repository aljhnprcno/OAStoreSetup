<?php

namespace App\Services\Research;

use Illuminate\Database\Eloquent\Model;

class ResearchRequest extends Model
{
    protected $fillable = [
        'research_id',
        'type',
        'entity_id',
        'branch_code',
        'is_accepted',
    ];

    public function research() {
        return $this->belongsTo(Research::class);
    }
}
