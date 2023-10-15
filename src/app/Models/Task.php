<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'name',
        'provider_id',
        'assignee_id',
        'difficulty',
        'estimated_duration_in_hours',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(Developer::class, 'assignee_id');
    }
}
