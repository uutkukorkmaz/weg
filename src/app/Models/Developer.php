<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Developer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'seniority',
    ];

    protected $casts = [
        'seniority' => 'integer',
    ];


    public function getWorkingHours(): int
    {
       return config('assignment.developer.working_hours');
    }

}
