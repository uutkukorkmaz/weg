<?php

namespace App\Models;

use App\Contracts\TaskProvider;
use App\Factories\ProviderFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
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

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'instance'
    ];

    public function instance(): Attribute
    {
        return Attribute::get(fn() => ProviderFactory::make($this));
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public static function from(string $name): ?self
    {
        return self::where('name', $name)->first();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
