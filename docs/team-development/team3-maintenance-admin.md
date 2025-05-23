# Team 3: Maintenance & Administration

## Team Overview
**Primary Responsibility**: Maintenance tracking, fuel management, system administration, and reporting
**Team Lead**: [To be assigned]
**Members**: [To be assigned]

## Core Responsibilities
- Maintenance scheduling and tracking system
- Fuel card management and monitoring
- Lookup data management (all reference tables)
- Reporting and analytics functionality
- System administration features

---

## Phase 1: Lookup Data & Foundation (Week 1-2)

### ✅ Lookup Tables Setup
- [ ] **LOOKUP-001**: Create all lookup table migrations
  - Vehicle types table
  - Positions (Jawatan) table
  - Application status table
  - Manufacturers table
  - Companies table
  - Vehicle models table
  - Vehicle categories table
  - Locations table
  - **Dependencies**: Team 1 (users table)
  - **Acceptance Criteria**: All lookup tables created

```sql
-- Key lookup tables structure
CREATE TABLE vehicle_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE positions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    grade VARCHAR(20),
    department VARCHAR(100),
    krj_eligible BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

- [ ] **LOOKUP-002**: Create lookup models
  - VehicleType model
  - Position model
  - ApplicationStatus model
  - Manufacturer model
  - Company model
  - VehicleModel model
  - VehicleCategory model
  - Location model
  - **Dependencies**: LOOKUP-001
  - **Acceptance Criteria**: All lookup models functional

- [ ] **LOOKUP-003**: Create lookup seeders
  - Populate vehicle types
  - Populate positions with KRJ eligibility
  - Populate application statuses
  - Populate manufacturers and models
  - **Dependencies**: LOOKUP-002
  - **Acceptance Criteria**: Lookup data available for development

### ✅ Maintenance System Foundation
- [ ] **MAINT-001**: Create maintenance-related migrations
  - Maintenance records table
  - Maintenance types table
  - Service vendors table
  - Maintenance schedules table
  - **Dependencies**: Team 2 (vehicles table), LOOKUP-001
  - **Acceptance Criteria**: Maintenance schema complete

```sql
-- maintenance_records table structure
CREATE TABLE maintenance_records (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vehicle_id BIGINT UNSIGNED NOT NULL,
    service_type ENUM('routine', 'repair', 'inspection', 'emergency'),
    description TEXT,
    cost DECIMAL(10,2),
    vendor VARCHAR(100),
    service_date DATE NOT NULL,
    next_service_date DATE,
    mileage_at_service INT,
    status ENUM('scheduled', 'in_progress', 'completed'),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
);
```

- [ ] **MAINT-002**: Create fuel card system migrations
  - Fuel cards table
  - Fuel usage records table
  - Fuel vendors table
  - Monthly limits table
  - **Dependencies**: Team 2 (vehicles table)
  - **Acceptance Criteria**: Fuel management schema ready

```sql
-- fuel_cards table structure
CREATE TABLE fuel_cards (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    card_number VARCHAR(20) UNIQUE NOT NULL,
    vehicle_id BIGINT UNSIGNED,
    monthly_limit DECIMAL(8,2),
    current_usage DECIMAL(8,2) DEFAULT 0,
    status ENUM('active', 'inactive', 'blocked'),
    issue_date DATE,
    expiry_date DATE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
);
```

---

## Phase 2: Maintenance System Implementation (Week 2-3)

### 🔄 Maintenance Models & Services
- [ ] **MODEL-001**: Create MaintenanceRecord model
  - Vehicle relationship
  - Cost tracking
  - Status management
  - File attachments (invoices, reports)
  - **Dependencies**: MAINT-001
  - **Acceptance Criteria**: Maintenance model with full functionality

```php
// File: app/Models/MaintenanceRecord.php
class MaintenanceRecord extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'vehicle_id', 'service_type', 'description', 'cost',
        'vendor', 'service_date', 'next_service_date',
        'mileage_at_service', 'status'
    ];
    
    protected $casts = [
        'service_date' => 'date',
        'next_service_date' => 'date',
        'cost' => 'decimal:2'
    ];
    
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
```

- [ ] **MODEL-002**: Create FuelCard model
  - Vehicle relationship
  - Usage tracking
  - Limit management
  - Transaction history
  - **Dependencies**: MAINT-002
  - **Acceptance Criteria**: Fuel card model functional

- [ ] **MODEL-003**: Create FuelUsage model
  - Transaction recording
  - Vendor integration
  - Cost calculation
  - Monthly reporting
  - **Dependencies**: MODEL-002
  - **Acceptance Criteria**: Fuel usage tracking complete

### 🔄 Maintenance Services
- [ ] **SERVICE-001**: Create MaintenanceService
  - Scheduling logic
  - Cost calculation
  - Vendor management
  - Notification triggers
  - **Dependencies**: MODEL-001
  - **Acceptance Criteria**: Maintenance service layer functional

- [ ] **SERVICE-002**: Create FuelManagementService
  - Usage monitoring
  - Limit enforcement
  - Vendor API integration
  - Alert generation
  - **Dependencies**: MODEL-003
  - **Acceptance Criteria**: Fuel management service complete

- [ ] **SERVICE-003**: Create ReportingService
  - Maintenance cost reports
  - Fuel consumption reports
  - Vehicle utilization reports
  - Performance analytics
  - **Dependencies**: SERVICE-001, SERVICE-002
  - **Acceptance Criteria**: Comprehensive reporting system

---

## Phase 3: Filament Resources & Interface (Week 3-4)

### ⏳ Maintenance Management Interface
- [ ] **UI-001**: Create MaintenanceResource
  - Maintenance record CRUD
  - Service scheduling
  - Cost tracking
  - File upload for invoices
  - **Dependencies**: SERVICE-001
  - **Acceptance Criteria**: Complete maintenance management interface

```php
// File: app/Filament/Resources/MaintenanceResource.php
class MaintenanceResource extends Resource
{
    protected static ?string $model = MaintenanceRecord::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Maintenance';
    
    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Service Information')->schema([
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'registration_number')
                    ->required(),
                Select::make('service_type')->options([
                    'routine' => 'Routine Maintenance',
                    'repair' => 'Repair',
                    'inspection' => 'Inspection',
                    'emergency' => 'Emergency'
                ])->required(),
                Textarea::make('description'),
                TextInput::make('vendor'),
            ]),
            Section::make('Cost & Schedule')->schema([
                TextInput::make('cost')->numeric()->prefix('RM'),
                DatePicker::make('service_date')->required(),
                DatePicker::make('next_service_date'),
                TextInput::make('mileage_at_service')->numeric(),
                Select::make('status')->options([
                    'scheduled' => 'Scheduled',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed'
                ]),
            ]),
            Section::make('Attachments')->schema([
                FileUpload::make('attachments')
                    ->multiple()
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->directory('maintenance-documents'),
            ]),
        ]);
    }
}
```

- [ ] **UI-002**: Create FuelCardResource
  - Fuel card management
  - Usage monitoring
  - Limit configuration
  - Transaction history
  - **Dependencies**: SERVICE-002
  - **Acceptance Criteria**: Fuel card management interface

- [ ] **UI-003**: Create all lookup resources
  - VehicleTypeResource
  - PositionResource
  - ManufacturerResource
  - CompanyResource
  - LocationResource
  - **Dependencies**: LOOKUP-002
  - **Acceptance Criteria**: All lookup data manageable

### ⏳ Reporting & Analytics Interface
- [ ] **UI-004**: Create reporting dashboard
  - Maintenance cost analytics
  - Fuel consumption trends
  - Vehicle utilization metrics
  - Performance indicators
  - **Dependencies**: SERVICE-003
  - **Acceptance Criteria**: Comprehensive reporting dashboard

- [ ] **UI-005**: Create export functionality
  - Excel export for all reports
  - PDF generation for official reports
  - Scheduled report delivery
  - Custom report builder
  - **Dependencies**: UI-004
  - **Acceptance Criteria**: Flexible export system

---

## Phase 4: Advanced Features & Integration (Week 4-5)

### ⏳ Advanced Maintenance Features
- [ ] **ADV-001**: Implement maintenance scheduling
  - Automatic scheduling based on mileage
  - Calendar integration with Team 2
  - Maintenance booking system
  - Conflict resolution
  - **Dependencies**: UI-001, Team 2 calendar
  - **Acceptance Criteria**: Automated maintenance scheduling

- [ ] **ADV-002**: Create maintenance alerts
  - Due date notifications
  - Cost threshold alerts
  - Performance degradation warnings
  - Vendor performance tracking
  - **Dependencies**: ADV-001
  - **Acceptance Criteria**: Proactive maintenance alerts

- [ ] **ADV-003**: Implement fuel vendor integration
  - API integration for usage data
  - Automatic transaction import
  - Real-time balance updates
  - Vendor reconciliation
  - **Dependencies**: UI-002
  - **Acceptance Criteria**: Automated fuel data sync

### ⏳ System Administration
- [ ] **ADMIN-001**: Create system configuration
  - Global settings management
  - Business rules configuration
  - Notification preferences
  - System maintenance tools
  - **Dependencies**: All previous tasks
  - **Acceptance Criteria**: Configurable system settings

- [ ] **ADMIN-002**: Implement data import/export
  - Bulk vehicle import
  - Maintenance history import
  - Data backup/restore
  - Migration tools
  - **Dependencies**: ADMIN-001
  - **Acceptance Criteria**: Data management tools

### ⏳ Integration & Testing
- [ ] **INT-001**: Integrate with Team 2 vehicle system
  - Vehicle status updates from maintenance
  - Booking system integration
  - Calendar synchronization
  - **Dependencies**: Team 2 completion, ADV-001
  - **Acceptance Criteria**: Seamless vehicle integration

- [ ] **INT-002**: Integrate with Team 1 user system
  - User permission checking
  - Department-based data filtering
  - Audit logging
  - **Dependencies**: Team 1 completion, ADMIN-001
  - **Acceptance Criteria**: Secure access control

---

## File Structure & Ownership

### 📁 Team 3 Owned Files
```
app/
├── Models/
│   ├── MaintenanceRecord.php ✅
│   ├── FuelCard.php ✅
│   ├── FuelUsage.php ✅
│   ├── VehicleType.php ✅
│   ├── Position.php ✅
│   ├── Manufacturer.php ✅
│   ├── Company.php ✅
│   ├── VehicleModel.php ✅
│   ├── VehicleCategory.php ✅
│   └── Location.php ✅
├── Services/
│   ├── MaintenanceService.php ✅
│   ├── FuelManagementService.php ✅
│   ├── ReportingService.php ✅
│   └── ImportExportService.php ✅
├── Policies/
│   ├── MaintenancePolicy.php ✅
│   ├── FuelCardPolicy.php ✅
│   └── LookupPolicy.php ✅
├── Filament/
│   ├── Resources/
│   │   ├── MaintenanceResource.php ✅
│   │   ├── FuelCardResource.php ✅
│   │   ├── VehicleTypeResource.php ✅
│   │   ├── PositionResource.php ✅
│   │   ├── ManufacturerResource.php ✅
│   │   ├── CompanyResource.php ✅
│   │   └── LocationResource.php ✅
│   ├── Pages/
│   │   ├── MaintenanceDashboard.php ✅
│   │   ├── ReportsPage.php ✅
│   │   └── SystemSettings.php ✅
│   └── Widgets/
│       ├── MaintenanceStatsWidget.php ✅
│       ├── FuelConsumptionWidget.php ✅
│       ├── CostAnalyticsWidget.php ✅
│       └── AlertsWidget.php ✅
├── Http/
│   ├── Controllers/
│   │   ├── ReportController.php ✅
│   │   └── ExportController.php ✅
│   └── Requests/
│       ├── MaintenanceRequest.php ✅
│       └── FuelCardRequest.php ✅
└── Jobs/
    ├── ImportFuelUsageJob.php ✅
    ├── GenerateReportJob.php ✅
    └── MaintenanceReminderJob.php ✅

database/
├── migrations/
│   ├── *_create_maintenance_records_table.php ✅
│   ├── *_create_fuel_cards_table.php ✅
│   ├── *_create_fuel_usage_table.php ✅
│   ├── *_create_vehicle_types_table.php ✅
│   ├── *_create_positions_table.php ✅
│   ├── *_create_manufacturers_table.php ✅
│   ├── *_create_companies_table.php ✅
│   ├── *_create_vehicle_models_table.php ✅
│   ├── *_create_vehicle_categories_table.php ✅
│   └── *_create_locations_table.php ✅
├── seeders/
│   ├── MaintenanceSeeder.php ✅
│   ├── FuelCardSeeder.php ✅
│   ├── VehicleTypeSeeder.php ✅
│   ├── PositionSeeder.php ✅
│   ├── ManufacturerSeeder.php ✅
│   └── LookupDataSeeder.php ✅
└── factories/
    ├── MaintenanceRecordFactory.php ✅
    ├── FuelCardFactory.php ✅
    └── FuelUsageFactory.php ✅

config/
├── maintenance.php ✅
├── fuel-management.php ✅
└── reporting.php ✅
```

---

## Dependencies & Integration Points

### 🔗 Requires from Other Teams
- **Team 1**: User authentication and authorization
- **Team 1**: User model for maintenance assignments
- **Team 1**: Permission checking middleware
- **Team 2**: Vehicle models and relationships
- **Team 2**: Booking system for maintenance scheduling
- **Team 2**: Calendar integration

### 🔗 Provides to Other Teams
- **Team 2**: Vehicle maintenance status updates
- **Team 2**: Maintenance booking interface
- **Team 1**: Lookup data for user positions
- **All Teams**: Reporting and analytics services

### 🔗 Shared Services
```php
// Maintenance status updates for vehicles
interface MaintenanceStatusInterface {
    public function updateVehicleStatus($vehicleId, $status);
    public function getMaintenanceSchedule($vehicleId);
    public function isVehicleUnderMaintenance($vehicleId);
}

// Reporting services for all teams
interface ReportingInterface {
    public function generateReport($type, $parameters);
    public function scheduleReport($type, $schedule, $recipients);
    public function exportData($model, $format);
}
```

---

## Testing Requirements

### 🧪 Unit Tests
- [ ] MaintenanceRecord model tests (relationships, calculations)
- [ ] FuelCard model tests (usage tracking, limits)
- [ ] MaintenanceService tests (scheduling, cost calculation)
- [ ] ReportingService tests (data aggregation, export)

### 🧪 Feature Tests
- [ ] Maintenance CRUD operations
- [ ] Fuel card management
- [ ] Report generation
- [ ] Data import/export
- [ ] Lookup data management

### 🧪 Integration Tests
- [ ] Vehicle status updates
- [ ] Calendar integration
- [ ] Cross-team data access
- [ ] External API integration

---

## Business Logic Implementation

### 🔧 Maintenance Scheduling Rules
```php
// Automatic maintenance scheduling
public function calculateNextMaintenance($vehicle, $lastMaintenance)
{
    $rules = [
        'routine' => [
            'mileage_interval' => 10000, // Every 10,000 km
            'time_interval' => 6, // Every 6 months
        ],
        'inspection' => [
            'mileage_interval' => 20000, // Every 20,000 km
            'time_interval' => 12, // Every 12 months
        ]
    ];
    
    // Calculate based on mileage and time
    $nextByMileage = $vehicle->mileage + $rules['routine']['mileage_interval'];
    $nextByTime = $lastMaintenance->service_date->addMonths($rules['routine']['time_interval']);
    
    // Return the earlier date
    return min($nextByMileage, $nextByTime);
}
```

### ⛽ Fuel Management Rules
```php
// Fuel usage monitoring
public function checkFuelLimits($fuelCard, $amount)
{
    $currentMonth = now()->format('Y-m');
    $monthlyUsage = $fuelCard->usageRecords()
        ->whereRaw("DATE_FORMAT(transaction_date, '%Y-%m') = ?", [$currentMonth])
        ->sum('amount');
    
    if (($monthlyUsage + $amount) > $fuelCard->monthly_limit) {
        throw new FuelLimitExceededException();
    }
    
    return true;
}
```

### 📊 Reporting Calculations
```php
// Cost analysis calculations
public function calculateMaintenanceCosts($period)
{
    return [
        'total_cost' => MaintenanceRecord::whereBetween('service_date', $period)->sum('cost'),
        'average_cost' => MaintenanceRecord::whereBetween('service_date', $period)->avg('cost'),
        'cost_by_type' => MaintenanceRecord::whereBetween('service_date', $period)
            ->groupBy('service_type')
            ->selectRaw('service_type, SUM(cost) as total')
            ->get(),
        'cost_by_vehicle' => MaintenanceRecord::with('vehicle')
            ->whereBetween('service_date', $period)
            ->groupBy('vehicle_id')
            ->selectRaw('vehicle_id, SUM(cost) as total')
            ->get()
    ];
}
```

---

## KRJ (Official Vehicle) Management

### 🚗 KRJ Specific Features
- [ ] **KRJ-001**: Implement position-based vehicle allocation
  - Position eligibility checking
  - Automatic assignment rules
  - Transfer workflows
  - **Dependencies**: LOOKUP-003 (positions)
  - **Acceptance Criteria**: KRJ allocation system functional

- [ ] **KRJ-002**: Create KRJ usage tracking
  - Official trip recording
  - Mileage monitoring
  - Purpose validation
  - **Dependencies**: Team 2 (booking system)
  - **Acceptance Criteria**: KRJ usage properly tracked

- [ ] **KRJ-003**: Implement KRJ maintenance priority
  - Priority scheduling for official vehicles
  - Emergency maintenance protocols
  - Cost center allocation
  - **Dependencies**: ADV-001
  - **Acceptance Criteria**: KRJ maintenance prioritized

---

## Definition of Done

### ✅ Phase Completion Criteria

**Phase 1 Complete When:**
- All lookup tables created and populated
- Maintenance and fuel card schema implemented
- Basic models functional
- Seeders provide development data

**Phase 2 Complete When:**
- Maintenance tracking system functional
- Fuel management system operational
- Service layer provides business logic
- Cost tracking and reporting basic functionality

**Phase 3 Complete When:**
- Filament resources fully functional
- Reporting dashboard operational
- Export functionality working
- All lookup data manageable

**Phase 4 Complete When:**
- Advanced features implemented
- Integration with other teams complete
- Testing coverage >85%
- Documentation complete

---

## Performance Considerations

### 🚀 Optimization Strategies
- [ ] **PERF-001**: Implement caching for lookup data
  - Cache frequently accessed lookup tables
  - Invalidate cache on updates
  - Optimize dropdown performance

- [ ] **PERF-002**: Optimize reporting queries
  - Use database aggregation functions
  - Implement query result caching
  - Paginate large reports

- [ ] **PERF-003**: Background job processing
  - Queue heavy report generation
  - Async fuel data import
  - Scheduled maintenance reminders

---

## Risk Mitigation

### ⚠️ Potential Risks
1. **External API Dependencies**: Fuel vendor API reliability
   - **Mitigation**: Implement fallback manual entry, retry mechanisms

2. **Data Volume**: Large maintenance history affecting performance
   - **Mitigation**: Implement archiving, pagination, indexing

3. **Integration Complexity**: Multiple team dependencies
   - **Mitigation**: Mock interfaces for development, phased integration

4. **Reporting Performance**: Complex calculations slowing system
   - **Mitigation**: Background processing, caching, pre-calculated metrics

### 🚨 Escalation Path
- **Technical Issues**: Team Lead → Technical Architect
- **Integration Issues**: Cross-team coordination meeting
- **Performance Issues**: Database optimization review
- **External API Issues**: Vendor escalation process

---

## Communication & Coordination

### 📅 Daily Standups
- Progress on maintenance and fuel systems
- Lookup data updates and dependencies
- Integration blockers with other teams

### 📅 Weekly Integration Meetings
- Cross-team dependency coordination
- Shared interface reviews
- Performance testing results

### 📋 Deliverables Timeline
- **Week 1**: Lookup data and foundation setup
- **Week 2**: Maintenance and fuel systems
- **Week 3**: Filament resources and reporting
- **Week 4**: Advanced features and KRJ management
- **Week 5**: Integration testing and optimization

---

**Last Updated**: [Current Date]
**Next Review**: [Weekly]
**Status**: 🔄 In Progress
