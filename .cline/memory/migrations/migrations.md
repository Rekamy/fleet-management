# Migrations Memory Bank

## Migration Order
The migrations must be executed in the following order to maintain referential integrity:

1. `2025_05_20_044715_create_vehicle_types_table.php`
2. `2025_05_20_044716_create_vehicles_table.php`
3. `2025_05_20_044717_create_drivers_table.php`
4. `2025_05_20_044718_create_bookings_table.php`

## Migration Details

### 1. Vehicle Types Migration
```php
Schema::create('vehicle_types', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->timestamps();
});
```
Purpose:
- Store vehicle categories
- Base table for vehicle relationships
- No foreign key dependencies

### 2. Vehicles Migration
```php
Schema::create('vehicles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vehicle_type_id')->constrained()->onDelete('restrict');
    $table->string('name');
    $table->string('plate_number')->unique();
    $table->enum('status', ['available', 'booked', 'maintenance'])->default('available');
    $table->timestamps();
});
```
Purpose:
- Store vehicle information
- Link to vehicle types
- Track vehicle status
- Ensure unique plate numbers
Dependencies:
- vehicle_types table

### 3. Drivers Migration
```php
Schema::create('drivers', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('license_number')->unique();
    $table->string('contact_number');
    $table->enum('status', ['available', 'on_duty', 'on_leave'])->default('available');
    $table->timestamps();
});
```
Purpose:
- Store driver information
- Track driver status
- Manage contact details
- Ensure unique license numbers
Dependencies:
- None

### 4. Bookings Migration
```php
Schema::create('bookings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vehicle_id')->constrained()->onDelete('restrict');
    $table->foreignId('driver_id')->constrained()->onDelete('restrict');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->dateTime('start_datetime');
    $table->dateTime('end_datetime');
    $table->text('purpose');
    $table->enum('status', [
        'pending',
        'approved',
        'rejected',
        'completed',
        'cancelled'
    ])->default('pending');
    $table->timestamps();
});
```
Purpose:
- Store booking records
- Link vehicles, drivers, and users
- Track booking status
- Manage scheduling
Dependencies:
- vehicles table
- drivers table
- users table

## Key Design Decisions

### Foreign Key Constraints
1. Vehicle Types → Vehicles:
   - onDelete: restrict
   - Prevents deletion of vehicle types in use

2. Vehicles → Bookings:
   - onDelete: restrict
   - Prevents deletion of vehicles with bookings

3. Drivers → Bookings:
   - onDelete: restrict
   - Prevents deletion of drivers with bookings

4. Users → Bookings:
   - onDelete: cascade
   - Removes bookings when user is deleted

### Enum Fields
1. Vehicle Status:
   ```php
   ['available', 'booked', 'maintenance']
   ```

2. Driver Status:
   ```php
   ['available', 'on_duty', 'on_leave']
   ```

3. Booking Status:
   ```php
   ['pending', 'approved', 'rejected', 'completed', 'cancelled']
   ```

### Unique Constraints
1. Vehicles:
   - plate_number (unique)

2. Drivers:
   - license_number (unique)

### Nullable Fields
1. Vehicle Types:
   - description (nullable)

### DateTime Fields
1. Bookings:
   - start_datetime
   - end_datetime
   (Both non-nullable to ensure proper scheduling)

## Database Diagram
```mermaid
erDiagram
    VehicleTypes {
        id int PK
        name string
        description text NULL
        created_at timestamp
        updated_at timestamp
    }
    Vehicles {
        id int PK
        vehicle_type_id int FK
        name string
        plate_number string UK
        status enum
        created_at timestamp
        updated_at timestamp
    }
    Drivers {
        id int PK
        name string
        license_number string UK
        contact_number string
        status enum
        created_at timestamp
        updated_at timestamp
    }
    Bookings {
        id int PK
        vehicle_id int FK
        driver_id int FK
        user_id int FK
        start_datetime datetime
        end_datetime datetime
        purpose text
        status enum
        created_at timestamp
        updated_at timestamp
    }
    VehicleTypes ||--o{ Vehicles : has
    Vehicles ||--o{ Bookings : has
    Drivers ||--o{ Bookings : has
    Users ||--o{ Bookings : creates
