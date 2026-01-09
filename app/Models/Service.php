<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection $items
 */
class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_code',
        'queue_number',
        'customer_name',
        'phone',
        'email',
        'device',
        'quantity',
        'complaint',
        'damage_note',
        'status',
        'payment_method',
        'payment_status',
        'total_price',
    ];

    
    /**
     * Get the items for the service.
     */
    public function items()
    {
        return $this->hasMany(ServiceItem::class);
    }

    /**
     * Recalculate and save the total price.
     */
    public function recalculateTotal()
    {
        $this->total_price = $this->items()->sum('price');
        $this->save();
    }
}
