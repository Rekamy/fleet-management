<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    const STATUS_AVAILABLE = 'available';
    const STATUS_ON_DUTY = 'on_duty';
    const STATUS_ON_LEAVE = 'on_leave';

    protected $fillable = [
        'name',
        'license_number',
        'contact_number',
        'status',
    ];

    /**
     * Get the bookings for this driver.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope a query to only include available drivers.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Check if the driver is available.
     */
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    /**
     * Get active bookings for the driver.
     */
    public function activeBookings()
    {
        return $this->bookings()
            ->whereIn('status', ['pending', 'approved'])
            ->where('end_datetime', '>=', now());
    }
}
