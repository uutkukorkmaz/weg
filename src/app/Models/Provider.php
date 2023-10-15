<?php

namespace App\Models;

use App\Contracts\TaskProvider;
use App\Factories\ProviderFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $url
 * @property string $resolver
 * @property bool $is_active
 * @property TaskProvider $instance
 */
class Provider extends Model
{
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
        return Attribute::get(fn() => ProviderFactory::make($this));
    }
}
