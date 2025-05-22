<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    const STATUS_AVAILABLE = 'available';
    const STATUS_BOOKED = 'booked';
    const STATUS_MAINTENANCE = 'maintenance';

    protected $fillable = [
        'vehicle_type_id',
        'name',
        'plate_number',
        'status',
    ];

    /**
     * Get the vehicle type of this vehicle.
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    /**
     * Get the bookings for this vehicle.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope a query to only include available vehicles.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Check if the vehicle is available.
     */
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }
}
