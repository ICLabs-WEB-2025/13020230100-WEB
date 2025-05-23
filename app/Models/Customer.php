<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'user_id' // Pastikan user_id dapat diisi
    ];

    protected $casts = [
        'phone_verified_at' => 'datetime'
    ];

    protected $appends = [
        'whatsapp_number',
        'local_phone_number'
    ];

    /**
     * Mutator untuk standarisasi format nomor telepon sebelum disimpan
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = $this->standardizePhoneNumber($value);
    }

    /**
     * Accessor untuk nomor telepon format internasional
     */
    public function getFormattedPhoneAttribute()
    {
        return $this->phone;
    }

    /**
     * Accessor untuk nomor WhatsApp (62xxxxxxxxxx)
     */
    public function getWhatsappNumberAttribute()
    {
        return $this->phone ? preg_replace('/^\+62/', '62', $this->phone) : null;
    }

    /**
     * Accessor untuk nomor format lokal (08xxxxxxxxx)
     */
    public function getLocalPhoneNumberAttribute()
    {
        return $this->phone ? preg_replace('/^\+62/', '0', $this->phone) : null;
    }

    /**
     * Validasi nomor telepon
     */
    public static function validatePhone($phone)
    {
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Format yang diterima:
        // +628xxxxxxxxxx
        // 628xxxxxxxxxx
        // 08xxxxxxxxx
        return preg_match('/^(\+62|62|0)8[1-9][0-9]{6,9}$/', $phone);
    }

    /**
     * Standarisasi nomor telepon ke format +62
     */
    protected function standardizePhoneNumber($phone)
    {
        // Bersihkan karakter khusus
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // Konversi berbagai format ke +62
        if (str_starts_with($phone, '0')) {
            return '+62' . substr($phone, 1);
        }

        if (str_starts_with($phone, '62')) {
            return '+' . $phone;
        }

        if (str_starts_with($phone, '8')) {
            return '+62' . $phone;
        }

        if (!str_starts_with($phone, '+')) {
            return '+62' . $phone;
        }

        return $phone;
    }

    /**
     * Verifikasi nomor telepon
     */
    public function verifyPhone()
    {
        $this->update([
            'phone_verified_at' => now(),
            'phone_verification_code' => null
        ]);
        
        Log::info('Nomor telepon diverifikasi', ['customer_id' => $this->id]);
    }

    /**
     * Relasi ke orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope untuk customer dengan nomor terverifikasi
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('phone_verified_at');
    }

    /**
     * Scope untuk mencari berdasarkan nomor telepon
     */
    public function scopeByPhone($query, $phone)
    {
        $standardized = $this->standardizePhoneNumber($phone);
        return $query->where('phone', $standardized);
    }
    
}