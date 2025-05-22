<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'user_id',
        'start_datetime',
        'end_datetime',
        'purpose',
        'status',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    /**
     * Get the vehicle for this booking.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the driver for this booking.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get the user who made the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active bookings.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED])
            ->where('end_datetime', '>=', now());
    }

    /**
     * Check if the booking can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED]) &&
            $this->start_datetime->isFuture();
    }

    /**
     * Check if the booking is active.
     */
    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED]) &&
            $this->end_datetime->isFuture();
    }

    /**
     * Check if the booking overlaps with another booking.
     */
    public function overlaps(self $booking): bool
    {
        return $this->start_datetime < $booking->end_datetime &&
            $this->end_datetime > $booking->start_datetime;
    }
}
