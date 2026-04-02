<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $sales_order_id
 * @property string $name
 * @property string $slug
 * @property string $sku
 * @property string $tags
 * @property string|null $description
 * @property string|null $cover_url
 * @property int $quantity
 * @property int $weight
 * @property float $price
 * @property float $total
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SalesOrder $salesOrder
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem whereCoverUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem whereSalesOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrderItem whereWeight($value)
 *
 * @mixin \Eloquent
 */
class SalesOrderItem extends Model
{
    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }
}
