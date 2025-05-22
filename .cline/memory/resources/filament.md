# Filament Resources Memory Bank

## Navigation Structure
```
- Dashboard
- Bookings
  - Bookings List
  - Calendar
- Staff Management
  - Drivers
- Vehicle Management
  - Vehicles
  - Vehicle Types
```

## VehicleTypeResource
```php
class VehicleTypeResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Vehicle Management';

    // Form Fields
    - name (TextInput, required)
    - description (Textarea)

    // Table Columns
    - name (searchable)
    - description (searchable, limited)
    - vehicles_count
    - timestamps (toggleable)

    // Features
    - Search functionality
    - Sorting capabilities
    - Vehicle count display
    - CRUD operations
```

## VehicleResource
```php
class VehicleResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Vehicle Management';

    // Form Fields
    - vehicle_type_id (Select with relationship)
    - name (TextInput, required)
    - plate_number (TextInput, unique)
    - status (Select with predefined options)

    // Table Columns
    - type.name (sortable, searchable)
    - name (searchable)
    - plate_number (searchable)
    - status (selectable, sortable)
    - timestamps (toggleable)

    // Features
    - Quick status updates
    - Type relationship management
    - Vehicle availability tracking
    - CRUD operations
```

## DriverResource
```php
class DriverResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Staff Management';

    // Form Fields
    - name (TextInput, required)
    - license_number (TextInput, unique)
    - contact_number (TextInput, tel)
    - status (Select with predefined options)

    // Table Columns
    - name (searchable)
    - license_number (searchable)
    - contact_number (searchable)
    - status (selectable, sortable)
    - timestamps (toggleable)

    // Features
    - Driver availability management
    - Contact information tracking
    - License management
    - CRUD operations
```

## BookingResource
```php
class BookingResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Bookings';

    // Form Fields
    - vehicle_id (Select with relationship)
    - driver_id (Select with filtered relationship)
    - start_datetime (DateTimePicker)
    - end_datetime (DateTimePicker)
    - purpose (Textarea)
    - status (Select with predefined options)

    // Table Columns
    - vehicle.name (sortable, searchable)
    - driver.name (sortable, searchable)
    - user.name (sortable, searchable)
    - start_datetime (sortable)
    - end_datetime (sortable)
    - purpose (searchable, limited)
    - status (selectable, sortable)

    // Filters
    - status (SelectFilter)
    - vehicle (RelationshipFilter)
    - driver (RelationshipFilter)
    - date range (Filter)

    // Features
    - Smart driver filtering based on availability
    - Date validation
    - Booking conflict prevention
    - Status workflow management
    - CRUD operations
```

## Calendar Page
```php
class Calendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Bookings';

    // Features
    - FullCalendar.js integration
    - Month/week/day views
    - Color-coded booking status
    - Direct booking access
    - Navigation controls
    - Real-time updates

    // Booking Display
    - Title: "{vehicle.name} - {driver.name}"
    - Status-based colors
    - Click-to-edit functionality
    - Date range visualization
```

## Key Features
1. Resource Organization:
   - Logical grouping by function
   - Clear navigation structure
   - Consistent icon usage

2. Form Handling:
   - Smart relationship selects
   - Validation rules
   - Real-time updates
   - Conflict prevention

3. Table Management:
   - Sortable columns
   - Search functionality
   - Status management
   - Relationship display

4. Filtering System:
   - Status filters
   - Date range filters
   - Relationship filters
   - Search capabilities

5. Calendar Integration:
   - Visual booking display
   - Interactive interface
   - Status color coding
   - Navigation controls

## Best Practices
1. Resource Organization:
   - Group related resources
   - Consistent naming
   - Clear navigation

2. Form Design:
   - Logical field grouping
   - Smart validation
   - Relationship handling
   - Status management

3. Table Layout:
   - Essential columns
   - Action placement
   - Status indicators
   - Search optimization

4. User Experience:
   - Quick actions
   - Clear feedback
   - Intuitive navigation
   - Status visibility
