<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Order.php
protected $fillable = [
    'customer_id',
    'service_id', // Ganti dari service_type
    'weight',
    'total_price', // Ganti dari amount
    'status',
    'pickup_date',
    'delivery_date',
    'notes'
];

protected $casts = [
    'pickup_date' => 'date',
    'delivery_date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
];

// Tambahkan relasi ke Service
public function service()
{
    return $this->belongsTo(Service::class);
}

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}