<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_id',
        'weight',
        'total_price',
        'status',
        'pickup_date',
        'delivery_date',
        'notes'
    ];

    // Relasi ke customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Accessor untuk status
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-secondary',
            'processing' => 'bg-info',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger'
        ];

        return '<span class="badge '.$badges[$this->status].'">'.ucfirst($this->status).'</span>';
    }
}