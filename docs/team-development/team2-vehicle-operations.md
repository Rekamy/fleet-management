# Team 2: Vehicle Management & Operations

## Team Overview
**Primary Responsibility**: Vehicle lifecycle management, polymorphic booking system, and operational features
**Team Lead**: [To be assigned]
**Members**: [To be assigned]

## Core Responsibilities
- Vehicle CRUD operations and management
- Polymorphic booking system (core implementation)
- Driver management and scheduling
- Calendar integration and conflict detection
- Vehicle availability tracking

---

## Phase 1: Core Vehicle Models (Week 1-2)

### ✅ Database Schema Design
- [ ] **DB-001**: Create vehicle-related migrations
  - Vehicles table with complete specifications
  - Vehicle types lookup table
  - Vehicle categories table
  - **Dependencies**: Team 1 (users table)
  - **Acceptance Criteria**: Vehicle schema supports all requirements

```sql
-- vehicles table structure
CREATE TABLE vehicles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    registration_number VARCHAR(20) UNIQUE NOT NULL,
    make VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    year YEAR NOT NULL,
    vehicle_type_id BIGINT UNSIGNED,
    capacity INT NOT NULL,
    fuel_type ENUM('petrol', 'diesel', 'hybrid', 'electric'),
    status ENUM('available', 'in_use', 'maintenance', 'retired'),
    mileage INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

- [ ] **DB-002**: Create driver-related migrations
  - Drivers table with license information
  - Driver schedules table
  - Driver vehicle assignments
  - **Dependencies**: DB-001
  - **Acceptance Criteria**: Driver management schema complete

- [ ] **DB-003**: Create polymorphic booking schema
  - Bookings table (polymorphic hub)
  - Vehicle bookings detail table
  - Booking status tracking
  - **Dependencies**: DB-001, DB-002
  - **Acceptance Criteria**: Polymorphic booking system ready

```sql
-- bookings table (polymorphic hub)
CREATE TABLE bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_number VARCHAR(20) UNIQUE NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    bookable_type VARCHAR(255) NOT NULL,
    bookable_id BIGINT UNSIGNED NOT NULL,
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'completed', 'cancelled'),
    approved_by BIGINT UNSIGNED NULL,
    approved_at TIMESTAMP NULL,
    rejection_reason TEXT NULL,
    special_requirements TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### ✅ Core Models Development
- [ ] **MODEL-001**: Create Vehicle model
  - Eloquent relationships
  - Scopes for availability
  - Polymorphic booking relationship
  - Status management methods
  - **Dependencies**: DB-001
  - **Acceptance Criteria**: Vehicle model with full functionality

```php
// File: app/Models/Vehicle.php
class Vehicle extends Model implements BookableInterface
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'registration_number', 'make', 'model', 'year',
        'vehicle_type_id', 'capacity', 'fuel_type', 'status', 'mileage'
    ];
    
    // Polymorphic relationship
    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
    
    // Availability checking
    public function isAvailable($startDate, $endDate)
    {
        // Implementation
    }
}
```

- [ ] **MODEL-002**: Create Driver model
  - License validation
  - Schedule relationships
  - Availability checking
  - **Dependencies**: DB-002
  - **Acceptance Criteria**: Driver model with scheduling support

- [ ] **MODEL-003**: Create Booking model (Polymorphic Hub)
  - Polymorphic relationships
  - Status management
  - Conflict detection
  - Approval workflow
  - **Dependencies**: MODEL-001, MODEL-002
  - **Acceptance Criteria**: Polymorphic booking system functional

- [ ] **MODEL-004**: Create VehicleBooking model (Detail Table)
  - Purpose enumeration
  - Passenger tracking
  - Distance estimation
  - **Dependencies**: MODEL-003
  - **Acceptance Criteria**: Vehicle booking details captured

---

## Phase 2: Booking System Implementation (Week 2-3)

### 🔄 Polymorphic Booking Core
- [ ] **BOOK-001**: Implement BookableInterface
  - Define contract for all bookable entities
  - Availability checking methods
  - Validation rules interface
  - **Dependencies**: MODEL-003
  - **Acceptance Criteria**: Interface ready for future expansion

```php
// File: app/Contracts/BookableInterface.php
interface BookableInterface
{
    public function bookings();
    public function isAvailable($startDate, $endDate);
    public function getBookingValidationRules();
    public function getBookingCapacity();
}
```

- [ ] **BOOK-002**: Create booking service layer
  - Conflict detection logic
  - Availability calculation
  - Booking creation workflow
  - Status management
  - **Dependencies**: BOOK-001
  - **Acceptance Criteria**: Robust booking service

- [ ] **BOOK-003**: Implement booking validation
  - Date/time validation
  - Conflict checking
  - Business rules enforcement
  - User permission validation
  - **Dependencies**: BOOK-002
  - **Acceptance Criteria**: Comprehensive validation system

### 🔄 Calendar Integration
- [ ] **CAL-001**: Install and configure Filament Calendar
  - Calendar plugin setup
  - Event data mapping
  - Custom styling
  - **Dependencies**: BOOK-003
  - **Acceptance Criteria**: Calendar displays bookings

- [ ] **CAL-002**: Implement calendar views
  - Monthly vehicle schedule view
  - Daily driver schedule view
  - Conflict highlighting
  - Drag-and-drop booking (optional)
  - **Dependencies**: CAL-001
  - **Acceptance Criteria**: Interactive calendar functionality

- [ ] **CAL-003**: Create calendar event management
  - Event creation from bookings
  - Real-time updates
  - Color coding by status
  - **Dependencies**: CAL-002
  - **Acceptance Criteria**: Calendar reflects booking changes

---

## Phase 3: Filament Resources (Week 3-4)

### ⏳ Vehicle Management Interface
- [ ] **UI-001**: Create VehicleResource
  - Complete CRUD interface
  - Vehicle specifications form
  - Status management
  - Availability display
  - **Dependencies**: CAL-003
  - **Acceptance Criteria**: Full vehicle management interface

```php
// File: app/Filament/Resources/VehicleResource.php
class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Fleet Management';
    
    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Information')->schema([
                TextInput::make('registration_number')->required(),
                TextInput::make('make')->required(),
                TextInput::make('model')->required(),
                TextInput::make('year')->numeric()->required(),
            ]),
            Section::make('Specifications')->schema([
                Select::make('vehicle_type_id')->relationship('vehicleType', 'name'),
                TextInput::make('capacity')->numeric(),
                Select::make('fuel_type')->options([
                    'petrol' => 'Petrol',
                    'diesel' => 'Diesel',
                    'hybrid' => 'Hybrid',
                    'electric' => 'Electric',
                ]),
                Select::make('status')->options([
                    'available' => 'Available',
                    'in_use' => 'In Use',
                    'maintenance' => 'Maintenance',
                    'retired' => 'Retired',
                ]),
            ]),
        ]);
    }
}
```

- [ ] **UI-002**: Create DriverResource
  - Driver profile management
  - License tracking
  - Schedule overview
  - Performance metrics
  - **Dependencies**: UI-001
  - **Acceptance Criteria**: Complete driver management

- [ ] **UI-003**: Create BookingResource
  - Booking request interface
  - Approval workflow
  - Status tracking
  - Conflict resolution
  - **Dependencies**: UI-002
  - **Acceptance Criteria**: Booking management system

### ⏳ Calendar Page Implementation
- [ ] **UI-004**: Create Calendar page
  - Custom Filament page for calendar
  - Vehicle schedule display
  - Driver schedule display
  - Booking creation from calendar
  - **Dependencies**: UI-003
  - **Acceptance Criteria**: Functional calendar page

- [ ] **UI-005**: Create booking widgets
  - Pending bookings widget
  - Vehicle utilization widget
  - Driver schedule widget
  - Conflict alerts widget
  - **Dependencies**: UI-004
  - **Acceptance Criteria**: Dashboard widgets functional

---

## Phase 4: Advanced Features (Week 4-5)

### ⏳ Booking Workflow
- [ ] **FLOW-001**: Implement approval workflow
  - Multi-level approval system
  - Email notifications
  - Status tracking
  - Rejection handling
  - **Dependencies**: UI-005
  - **Acceptance Criteria**: Complete approval process

- [ ] **FLOW-002**: Create booking modifications
  - Edit existing bookings
  - Cancellation workflow
  - Rescheduling functionality
  - Change impact analysis
  - **Dependencies**: FLOW-001
  - **Acceptance Criteria**: Flexible booking management

- [ ] **FLOW-003**: Implement recurring bookings
  - Weekly/monthly patterns
  - Exception handling
  - Bulk operations
  - **Dependencies**: FLOW-002
  - **Acceptance Criteria**: Recurring booking support

### ⏳ Integration & Testing
- [ ] **INT-001**: Integrate with Team 1 authentication
  - User permission checking
  - Department-based filtering
  - Role-based access control
  - **Dependencies**: FLOW-003, Team 1 completion
  - **Acceptance Criteria**: Secure access control

- [ ] **INT-002**: Prepare integration points for Team 3
  - Maintenance booking interface
  - Fuel card integration hooks
  - Vehicle status updates
  - **Dependencies**: INT-001
  - **Acceptance Criteria**: Team 3 can integrate

---

## File Structure & Ownership

### 📁 Team 2 Owned Files
```
app/
├── Models/
│   ├── Vehicle.php ✅
│   ├── Driver.php ✅
│   ├── Booking.php ✅ (Polymorphic Hub)
│   ├── VehicleBooking.php ✅
│   └── VehicleType.php ✅
├── Contracts/
│   └── BookableInterface.php ✅
├── Services/
│   ├── BookingService.php ✅
│   ├── VehicleService.php ✅
│   └── CalendarService.php ✅
├── Policies/
│   ├── VehiclePolicy.php ✅
│   ├── DriverPolicy.php ✅
│   └── BookingPolicy.php ✅
├── Filament/
│   ├── Resources/
│   │   ├── VehicleResource.php ✅
│   │   ├── DriverResource.php ✅
│   │   ├── BookingResource.php ✅
│   │   └── VehicleTypeResource.php ✅
│   ├── Pages/
│   │   ├── Calendar.php ✅
│   │   └── VehicleSchedule.php ✅
│   └── Widgets/
│       ├── VehicleStatsWidget.php ✅
│       ├── BookingStatsWidget.php ✅
│       └── CalendarWidget.php ✅
└── Http/
    └── Requests/
        ├── BookingRequest.php ✅
        └── VehicleRequest.php ✅

database/
├── migrations/
│   ├── *_create_vehicles_table.php ✅
│   ├── *_create_drivers_table.php ✅
│   ├── *_create_bookings_table.php ✅
│   ├── *_create_vehicle_bookings_table.php ✅
│   └── *_create_vehicle_types_table.php ✅
├── seeders/
│   ├── VehicleSeeder.php ✅
│   ├── DriverSeeder.php ✅
│   └── VehicleTypeSeeder.php ✅
└── factories/
    ├── VehicleFactory.php ✅
    ├── DriverFactory.php ✅
    └── BookingFactory.php ✅

resources/
└── views/
    └── filament/
        └── pages/
            └── calendar.blade.php ✅
```

---

## Dependencies & Integration Points

### 🔗 Requires from Other Teams
- **Team 1**: User authentication and authorization
- **Team 1**: User model and relationships
- **Team 1**: Permission checking middleware
- **Team 3**: Vehicle maintenance status updates

### 🔗 Provides to Other Teams
- **Team 3**: Vehicle models and relationships
- **Team 3**: Booking system for maintenance scheduling
- **Team 1**: Booking user relationships
- **All Teams**: Polymorphic booking interface

### 🔗 Shared Interfaces
```php
// BookableInterface - for future expansion
interface BookableInterface {
    public function bookings();
    public function isAvailable($startDate, $endDate);
    public function getBookingValidationRules();
}

// Booking events for notifications
class BookingCreated extends Event;
class BookingApproved extends Event;
class BookingCancelled extends Event;
```

---

## Testing Requirements

### 🧪 Unit Tests
- [ ] Vehicle model tests (relationships, scopes, availability)
- [ ] Driver model tests (schedule conflicts, availability)
- [ ] Booking model tests (polymorphic relationships, validation)
- [ ] BookingService tests (conflict detection, creation)

### 🧪 Feature Tests
- [ ] Vehicle CRUD operations
- [ ] Booking creation workflow
- [ ] Calendar functionality
- [ ] Approval process
- [ ] Conflict detection

### 🧪 Integration Tests
- [ ] Polymorphic booking system
- [ ] Calendar integration
- [ ] Cross-team authentication
- [ ] Notification system

---

## Business Logic Implementation

### 🚗 Vehicle Availability Rules
```php
// Vehicle availability checking
public function isAvailable($startDate, $endDate)
{
    // Check vehicle status
    if ($this->status !== 'available') {
        return false;
    }
    
    // Check for conflicting bookings
    $conflicts = $this->bookings()
        ->where('status', '!=', 'cancelled')
        ->where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('start_datetime', [$startDate, $endDate])
                  ->orWhereBetween('end_datetime', [$startDate, $endDate])
                  ->orWhere(function ($q) use ($startDate, $endDate) {
                      $q->where('start_datetime', '<=', $startDate)
                        ->where('end_datetime', '>=', $endDate);
                  });
        })->exists();
    
    return !$conflicts;
}
```

### 📅 Booking Purpose Types
```php
// Vehicle booking purposes (from BRS)
const BOOKING_PURPOSES = [
    'meeting' => 'Mesyuarat',
    'training' => 'Latihan',
    'site_visit' => 'Lawatan Tapak',
    'audit' => 'Audit',
    'replacement_driver' => 'Pemandu Ganti',
    'shared_driver' => 'Pemandu Berkongsi',
    'goods_delivery' => 'Penghantaran Barang',
    'secretariat' => 'Sekretariat',
    'krj_maintenance' => 'Penyelenggaraan KRJ',
    'others' => 'Lain-lain'
];
```

### ⏰ Business Hours Validation
```php
// Business hours checking
public function isWithinBusinessHours($datetime)
{
    $hour = Carbon::parse($datetime)->hour;
    return $hour >= 8 && $hour <= 17; // 8 AM to 5 PM
}
```

---

## Definition of Done

### ✅ Phase Completion Criteria

**Phase 1 Complete When:**
- Vehicle and driver models functional
- Polymorphic booking schema implemented
- Core relationships established
- Basic CRUD operations working

**Phase 2 Complete When:**
- Booking system handles conflicts
- Calendar integration functional
- Availability checking accurate
- Validation rules comprehensive

**Phase 3 Complete When:**
- Filament resources fully functional
- Calendar page interactive
- Dashboard widgets displaying data
- User interface intuitive

**Phase 4 Complete When:**
- Approval workflow complete
- Integration with other teams successful
- Testing coverage >85%
- Documentation complete

---

## Performance Considerations

### 🚀 Optimization Strategies
- [ ] **PERF-001**: Implement eager loading for relationships
  - Prevent N+1 queries in vehicle listings
  - Optimize booking queries with user data
  - Cache frequently accessed data

- [ ] **PERF-002**: Database indexing strategy
  - Index on booking dates for conflict checking
  - Index on vehicle status for availability
  - Composite indexes for complex queries

- [ ] **PERF-003**: Calendar performance optimization
  - Lazy loading for large date ranges
  - Pagination for booking lists
  - Caching for calendar events

---

## Risk Mitigation

### ⚠️ Potential Risks
1. **Polymorphic Complexity**: Over-engineering the booking system
   - **Mitigation**: Start with vehicle bookings, expand gradually

2. **Calendar Performance**: Slow loading with many bookings
   - **Mitigation**: Implement pagination and lazy loading

3. **Conflict Detection**: Race conditions in booking creation
   - **Mitigation**: Database-level constraints and locking

4. **Integration Dependencies**: Waiting for Team 1 authentication
   - **Mitigation**: Mock authentication for development

### 🚨 Escalation Path
- **Technical Issues**: Team Lead → Technical Architect
- **Integration Issues**: Cross-team leads coordination
- **Performance Issues**: Database optimization review

---

## Communication & Coordination

### 📅 Daily Standups
- Progress on current development tasks
- Integration blockers with other teams
- Calendar and booking system updates

### 📅 Weekly Integration Meetings
- Polymorphic interface reviews
- Cross-team dependency coordination
- Performance testing results

### 📋 Deliverables Timeline
- **Week 1**: Core models and database schema
- **Week 2**: Booking system and calendar integration
- **Week 3**: Filament resources and UI
- **Week 4**: Advanced features and workflows
- **Week 5**: Integration testing and optimization

---

**Last Updated**: [Current Date]
**Next Review**: [Weekly]
**Status**: 🔄 In Progress
