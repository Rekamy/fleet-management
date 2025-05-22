<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create vehicle types
        $sedan = VehicleType::create([
            'name' => 'Sedan',
            'description' => 'Standard 4-door sedan vehicle',
        ]);

        $suv = VehicleType::create([
            'name' => 'SUV',
            'description' => 'Sport Utility Vehicle with higher clearance',
        ]);

        $van = VehicleType::create([
            'name' => 'Van',
            'description' => 'Multi-purpose van for larger groups',
        ]);

        // Create vehicles
        Vehicle::create([
            'vehicle_type_id' => $sedan->id,
            'name' => 'Toyota Camry',
            'plate_number' => 'ABC123',
            'status' => Vehicle::STATUS_AVAILABLE,
        ]);

        Vehicle::create([
            'vehicle_type_id' => $suv->id,
            'name' => 'Honda CR-V',
            'plate_number' => 'XYZ789',
            'status' => Vehicle::STATUS_AVAILABLE,
        ]);

        Vehicle::create([
            'vehicle_type_id' => $van->id,
            'name' => 'Toyota HiAce',
            'plate_number' => 'DEF456',
            'status' => Vehicle::STATUS_AVAILABLE,
        ]);

        // Create drivers
        Driver::create([
            'name' => 'John Smith',
            'license_number' => 'DL123456',
            'contact_number' => '+1234567890',
            'status' => Driver::STATUS_AVAILABLE,
        ]);

        Driver::create([
            'name' => 'Jane Doe',
            'license_number' => 'DL789012',
            'contact_number' => '+1987654321',
            'status' => Driver::STATUS_AVAILABLE,
        ]);

        Driver::create([
            'name' => 'Mike Johnson',
            'license_number' => 'DL345678',
            'contact_number' => '+1122334455',
            'status' => Driver::STATUS_AVAILABLE,
        ]);
    }
}
