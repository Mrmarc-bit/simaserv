<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'item_name',
        'price',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (ServiceItem $item) {
            $item->service->recalculateTotal();
        });

        static::updated(function (ServiceItem $item) {
            $item->service->recalculateTotal();
        });

        static::deleted(function (ServiceItem $item) {
            $item->service->recalculateTotal();
        });
    }
}
