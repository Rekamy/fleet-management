# System Patterns - Fleet Management System

## Core Architecture Pattern

### Polymorphic Booking System
**Pattern**: Single Table Inheritance with Polymorphic Relationships
- **Main Hub**: `bookings` table with `bookable_type` and `bookable_id`
- **Detail Tables**: `vehicle_bookings`, `facility_bookings` (future), `room_bookings` (future)
- **Benefits**: Scalable, consistent workflows, unified reporting

```
bookings (polymorphic hub)
├── vehicle_bookings (details)
├── facility_bookings (future)
└── room_bookings (future)
```

### Laravel Filament Admin Pattern
**Pattern**: Resource-Based Admin Panel
- **Resources**: CRUD interfaces for each entity
- **Pages**: Custom dashboard and calendar views
- **Widgets**: Statistics and data visualization
- **Actions**: Approval workflows and bulk operations

## Key Technical Decisions

### Database Design Patterns

#### 1. Polymorphic Relationships
```php
// Booking Model
public function bookable() {
    return $this->morphTo();
}

// Vehicle Model  
public function bookings() {
    return $this->morphMany(Booking::class, 'bookable');
}
```

#### 2. Separation of Concerns
- **General Data**: bookings table (dates, status, approval)
- **Specific Data**: vehicle_bookings table (purpose, destination, passengers)
- **Lookup Data**: Separate tables for all reference data

#### 3. Audit Trail Pattern
- **created_at/updated_at**: Standard Laravel timestamps
- **approved_by/approved_at**: Approval tracking
- **Status Tracking**: Enum fields for clear state management

### Filament Resource Patterns

#### 1. Resource Configuration
```php
// Standard pattern for all resources
class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Fleet Management';
}
```

#### 2. Form Schema Pattern
```php
public static function form(Form $form): Form
{
    return $form->schema([
        // Grouped sections for better UX
        Section::make('Basic Information')->schema([...]),
        Section::make('Technical Details')->schema([...]),
        Section::make('Status')->schema([...]),
    ]);
}
```

#### 3. Table Configuration Pattern
```php
public static function table(Table $table): Table
{
    return $table
        ->columns([...])
        ->filters([...])
        ->actions([...])
        ->bulkActions([...]);
}
```

## Design Patterns in Use

### 1. Repository Pattern (Implicit via Eloquent)
- **Models**: Represent business entities
- **Relationships**: Define data connections
- **Scopes**: Reusable query logic

### 2. Observer Pattern (Laravel Events)
- **Booking Events**: Created, approved, cancelled
- **Maintenance Events**: Scheduled, completed
- **Notification Triggers**: Email, SMS alerts

### 3. Strategy Pattern (Polymorphic Booking)
- **BookingStrategy**: Different booking types
- **ValidationStrategy**: Type-specific validation
- **ApprovalStrategy**: Different approval workflows

### 4. Factory Pattern (Laravel Factories)
- **Test Data Generation**: Consistent test data
- **Seeding**: Development environment setup
- **Demo Data**: Training and presentation

## Component Relationships

### Core Entity Relationships
```
User ──┐
       ├── Booking (polymorphic hub)
       │   ├── Vehicle ── VehicleBooking
       │   ├── Facility ── FacilityBooking (future)
       │   └── Room ── RoomBooking (future)
       │
       └── Approval (approved_by)

Vehicle ──┐
          ├── MaintenanceRecord
          ├── FuelCard
          └── VehicleType

Driver ── VehicleBooking
```

### Filament Component Hierarchy
```
AdminPanelProvider
├── Resources/
│   ├── VehicleResource
│   ├── BookingResource
│   ├── DriverResource
│   └── MaintenanceResource
├── Pages/
│   ├── Dashboard
│   └── Calendar
└── Widgets/
    ├── StatsOverview
    ├── CalendarWidget
    └── RecentActivity
```

## Critical Implementation Paths

### 1. Booking Creation Flow
```
User Request → Validation → Conflict Check → Approval Routing → Notification
```

### 2. Polymorphic Resolution
```
Booking → bookable_type/id → Specific Model → Detail Table → Business Logic
```

### 3. Calendar Integration
```
Booking Data → Calendar Events → Conflict Detection → Visual Display
```

### 4. Approval Workflow
```
Request → Role Check → Approval Rules → Status Update → Notifications
```

## Performance Patterns

### 1. Eager Loading Strategy
```php
// Prevent N+1 queries
Booking::with(['bookable', 'user', 'approver'])->get();
```

### 2. Caching Strategy
- **Static Data**: Vehicle types, positions (Redis)
- **Session Data**: User preferences, filters
- **Query Results**: Dashboard statistics

### 3. Database Indexing
```sql
-- Performance critical indexes
INDEX idx_bookable (bookable_type, bookable_id)
INDEX idx_dates (start_datetime, end_datetime)
INDEX idx_status (status)
```

## Security Patterns

### 1. Authorization Pattern
```php
// Policy-based authorization
class VehiclePolicy
{
    public function view(User $user, Vehicle $vehicle)
    {
        return $user->can('view vehicles') || 
               $user->department === $vehicle->department;
    }
}
```

### 2. Data Filtering Pattern
```php
// Scope-based data isolation
public function scopeForUser($query, User $user)
{
    if (!$user->isAdmin()) {
        return $query->where('department_id', $user->department_id);
    }
    return $query;
}
```

### 3. Input Validation Pattern
```php
// Form request validation
public function rules()
{
    return [
        'start_datetime' => 'required|date|after:now',
        'end_datetime' => 'required|date|after:start_datetime',
        'bookable_type' => 'required|in:App\Models\Vehicle',
    ];
}
```

## Integration Patterns

### 1. External API Pattern
```php
// Service class for external integrations
class FuelVendorService
{
    public function syncUsageData(FuelCard $card)
    {
        // API integration logic
    }
}
```

### 2. Event-Driven Notifications
```php
// Event listeners for notifications
class BookingApproved
{
    public function handle(BookingApprovedEvent $event)
    {
        // Send email, SMS, update calendar
    }
}
```

### 3. Import/Export Pattern
```php
// Filament import/export actions
public static function getActions(): array
{
    return [
        ImportAction::make()->importer(VehicleImporter::class),
        ExportAction::make()->exporter(VehicleExporter::class),
    ];
}
```

## Scalability Considerations

### 1. Horizontal Scaling
- **Database**: Read replicas for reporting
- **Cache**: Redis cluster for high availability
- **Queue**: Multiple workers for background jobs

### 2. Modular Architecture
- **Polymorphic Design**: Easy addition of new booking types
- **Service Classes**: Reusable business logic
- **Event System**: Decoupled notifications and integrations

### 3. Future Expansion Points
- **Mobile API**: Laravel Sanctum ready
- **Microservices**: Service-oriented architecture ready
- **Multi-tenancy**: Department-based isolation foundation
