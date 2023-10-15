<?php

namespace App\Models;

use App\Contracts\TaskProvider;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'resolver',
        'is_active',
    ];

    protected $appends = [
        'instance'
    ];

    public function instance(): Attribute
    {
        return Attribute::get(fn() => app($this->resolver, [$this]));
    }
}
