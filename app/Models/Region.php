<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $type
 * @property string|null $postal_code
 * @property string|null $parent_code
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Region> $children
 * @property-read int|null $children_count
 * @property-read Region|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region district()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region province()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region regency()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region village()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereParentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereType($value)
 * @mixin \Eloquent
 */
class Region extends Model
{
    //
    public $timestamps = false;

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'parent_code', 'code');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Region::class, 'parent_code', 'code');
    }

    public function scopeProvince($query)
    {
        return $query->where('type', 'province');
    }

    public function scopeRegency($query)
    {
        return $query->where('type', 'regency');
    }

    public function scopeDistrict($query)
    {
        return $query->where('type', 'district');
    }

    public function scopeVillage($query)
    {
        return $query->where('type', 'village');
    }
}
