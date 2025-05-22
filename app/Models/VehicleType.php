<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the vehicles of this type.
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
