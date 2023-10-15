<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Task extends Model
{
    protected $fillable = [
        'name',
        'provider_id',
        'difficulty',
        'estimated_duration_in_hours',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

}
