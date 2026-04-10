<?php

namespace App\Models;

use App\States\SalesOrder\SalesOrderState;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\ModelStates\HasStates;

/**
 * @property int $id
 * @property string $trx_id
 * @property SalesOrderState $status
 * @property string $customer_full_name
 * @property string $customer_email_address
 * @property string $customer_phone_number
 * @property string $address_line
 * @property string $origin_code
 * @property string $origin_province
 * @property string $origin_regency
 * @property string $origin_district
 * @property string $origin_village
 * @property string $origin_postal_code
 * @property string $destination_code
 * @property string $destination_province
 * @property string $destination_regency
 * @property string $destination_district
 * @property string $destination_village
 * @property string $destination_postal_code
 * @property string $shipping_driver
 * @property string $shipping_receipt_number
 * @property string $shipping_courier
 * @property string $shipping_service
 * @property string $shipping_estimated_delivery
 * @property float $shipping_cost
 * @property int $shipping_weight
 * @property string $payment_driver
 * @property string $payment_method
 * @property string $payment_label
 * @property array<array-key, mixed> $payment_payload
 * @property string|null $payment_paid_at
 * @property float $sub_total
 * @property float $shipping_total
 * @property float $grand_total
 * @property string $due_date_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, SalesOrderItem> $items
 * @property-read int|null $items_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder orWhereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder orWhereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereAddressLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereCustomerEmailAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereCustomerFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereCustomerPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereDestinationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereDestinationDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereDestinationPostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereDestinationProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereDestinationRegency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereDestinationVillage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereDueDateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereOriginCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereOriginDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereOriginPostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereOriginProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereOriginRegency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereOriginVillage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder wherePaymentDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder wherePaymentLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder wherePaymentPaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder wherePaymentPayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereShippingCourier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereShippingDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereShippingEstimatedDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereShippingReceiptNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereShippingService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereShippingTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereShippingWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereTrxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalesOrder whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class SalesOrder extends Model
{
    use HasStates, LogsActivity;

    protected $with = ['items'];

    protected function casts(): array
    {
        return [
            'status' => SalesOrderState::class,
            'payment_payload' => 'array',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'grand_total']);
    }
}
