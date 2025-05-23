# Software Requirements Specification (SRS)
## Fleet Management System - Laravel Filament Implementation

### Document Information
- **Document Version**: 1.0
- **Date**: May 2025
- **Project**: Fleet Management System
- **Document Type**: Software Requirements Specification
- **Technology Stack**: Laravel 11 + Filament 3.x
- **Based on**: BRS v1.0

---

## Table of Contents
1. [Introduction](#1-introduction)
2. [System Overview](#2-system-overview)
3. [Technology Stack](#3-technology-stack)
4. [Functional Requirements](#4-functional-requirements)
5. [Filament Resource Specifications](#5-filament-resource-specifications)
6. [Database Design](#6-database-design)
7. [User Interface Requirements](#7-user-interface-requirements)
8. [Security Requirements](#8-security-requirements)
9. [Performance Requirements](#9-performance-requirements)
10. [Integration Requirements](#10-integration-requirements)
11. [Testing Requirements](#11-testing-requirements)
12. [Deployment Requirements](#12-deployment-requirements)
13. [Maintenance Requirements](#13-maintenance-requirements)

---

## 1. Introduction

### 1.1 Purpose
This Software Requirements Specification (SRS) defines the technical implementation requirements for the Fleet Management System using Laravel Filament framework. It translates business requirements from the BRS into detailed technical specifications.

### 1.2 Scope
The system will be implemented as a Laravel Filament admin panel providing comprehensive fleet management capabilities including vehicle booking, maintenance tracking, fuel card management, and administrative functions.

### 1.3 Document Conventions
- **SRS-FR-XXX**: Functional Requirements
- **SRS-NFR-XXX**: Non-Functional Requirements
- **SRS-UI-XXX**: User Interface Requirements
- **SRS-DB-XXX**: Database Requirements
- **SRS-API-XXX**: API Requirements

### 1.4 References
- Business Requirements Specification (BRS) v1.0
- Laravel 11 Documentation
- Filament 3.x Documentation
- KRISA Guidelines by JDN

---

## 2. System Overview

### 2.1 System Architecture
The Fleet Management System will be built using a modern Laravel Filament architecture:

```
┌─────────────────────────────────────────┐
│           Filament Admin Panel          │
├─────────────────────────────────────────┤
│              Laravel 11                 │
├─────────────────────────────────────────┤
│               MySQL 8.0                 │
└─────────────────────────────────────────┘
```

### 2.2 Core Components
- **Filament Resources**: CRUD interfaces for all entities
- **Filament Pages**: Custom dashboard and calendar views
- **Filament Widgets**: Statistics and data visualization
- **Laravel Models**: Eloquent ORM for data management
- **Laravel Policies**: Authorization and access control

---

## 3. Technology Stack

### 3.1 Backend Framework
- **SRS-TECH-001**: Laravel Framework 11.x
- **SRS-TECH-002**: PHP 8.3 or higher
- **SRS-TECH-003**: Composer for dependency management

### 3.2 Admin Panel Framework
- **SRS-TECH-004**: Filament 3.x for admin interface
- **SRS-TECH-005**: Livewire 3.x for reactive components
- **SRS-TECH-006**: Alpine.js for frontend interactions

### 3.3 Database
- **SRS-TECH-007**: MySQL 8.0 as primary database
- **SRS-TECH-008**: Laravel Migrations for schema management
- **SRS-TECH-009**: Eloquent ORM for data access

### 3.4 Additional Packages
- **SRS-TECH-010**: Spatie Laravel Permission for role management
- **SRS-TECH-011**: Laravel Sanctum for API authentication
- **SRS-TECH-012**: Filament Calendar plugin for scheduling
- **SRS-TECH-013**: Filament Import/Export for data management

---

## 4. Functional Requirements

### 4.1 Vehicle Management Module

#### 4.1.1 Vehicle Booking System
- **SRS-FR-001**: System shall implement VehicleBookingResource with Filament table and form components
- **SRS-FR-002**: Booking form shall include the following fields:
  - Date/Time picker (required)
  - Purpose dropdown with 10 predefined options
  - Vehicle type selection
  - Destination (text field)
  - Number of passengers (numeric)
  - Special requirements (textarea)
- **SRS-FR-003**: System shall validate booking conflicts using Laravel validation rules
- **SRS-FR-004**: Booking approval workflow shall use Filament actions and notifications
- **SRS-FR-005**: Calendar view shall be implemented using Filament Calendar plugin

#### 4.1.2 Vehicle Maintenance Management
- **SRS-FR-006**: MaintenanceResource shall track service history with file upload capability
- **SRS-FR-007**: Maintenance scheduling shall integrate with vehicle availability
- **SRS-FR-008**: Cost tracking shall include vendor management and invoice uploads
- **SRS-FR-009**: Maintenance alerts shall use Laravel scheduled tasks and Filament notifications

#### 4.1.3 KRJ (Official Vehicle) Management
- **SRS-FR-010**: KRJAssignmentResource shall manage position-based vehicle allocation
- **SRS-FR-011**: Usage tracking shall record mileage and trip purposes
- **SRS-FR-012**: Transfer functionality shall update assignments with approval workflow

#### 4.1.4 Fuel Card Management
- **SRS-FR-013**: FuelCardResource shall manage card allocation and limits
- **SRS-FR-014**: Usage tracking shall import data from fuel vendor APIs
- **SRS-FR-015**: Cost reporting shall generate monthly consumption reports

### 4.2 User Management Module

#### 4.2.1 User Administration
- **SRS-FR-016**: UserResource shall implement full CRUD with Spatie roles integration
- **SRS-FR-017**: User impersonation shall be available for admin users
- **SRS-FR-018**: Profile management shall include contact information and preferences

#### 4.2.2 Access Control
- **SRS-FR-019**: Role-based access control shall use Filament policies
- **SRS-FR-020**: Permission matrix shall control resource access per user role
- **SRS-FR-021**: Department-based data filtering shall use Eloquent scopes

#### 4.2.3 Authentication
- **SRS-FR-022**: First-time user setup shall use Filament custom pages
- **SRS-FR-023**: Password reset shall integrate with Laravel's built-in functionality
- **SRS-FR-024**: Session management shall implement automatic timeout

### 4.3 Administration Module

#### 4.3.1 Lookup Data Management
- **SRS-FR-025**: Lookup tables shall be implemented as Filament resources:
  - PositionResource (Jawatan)
  - ApplicationStatusResource (Status Permohonan)
  - ManufacturerResource (Pembuat Kereta)
  - CompanyResource (Syarikat)
  - VehicleModelResource (Model Kenderaan)
  - VehicleCategoryResource (Kategori Kenderaan)
  - LocationResource (Lokasi Menunggu)
  - VehicleTypeResource (Jenis Kenderaan)

#### 4.3.2 Vehicle Registration
- **SRS-FR-026**: VehicleResource shall include complete vehicle specifications
- **SRS-FR-027**: Document management shall support file uploads and categorization
- **SRS-FR-028**: Procurement officer assignment shall link to user management

#### 4.3.3 Driver Schedule Management
- **SRS-FR-029**: DriverScheduleResource shall provide daily schedule management
- **SRS-FR-030**: Schedule conflicts shall be detected and highlighted
- **SRS-FR-031**: Driver availability tracking shall integrate with booking system

---

## 5. Filament Resource Specifications

### 5.1 VehicleResource
```php
// Resource Configuration
- Table: vehicles
- Model: App\Models\Vehicle
- Policy: App\Policies\VehiclePolicy
- Form Fields: registration, make, model, year, capacity, status
- Table Columns: registration, make_model, status, availability
- Filters: status, vehicle_type, availability
- Actions: view, edit, delete, assign_driver, schedule_maintenance
- Bulk Actions: export, bulk_status_update
```

### 5.2 BookingResource
```php
// Resource Configuration
- Table: bookings
- Model: App\Models\Booking
- Policy: App\Policies\BookingPolicy
- Form Fields: vehicle_id, user_id, start_date, end_date, purpose, destination
- Table Columns: vehicle, user, dates, purpose, status
- Filters: date_range, status, purpose, vehicle_type
- Actions: approve, reject, modify, cancel
- Custom Pages: calendar_view, approval_dashboard
```

### 5.3 DriverResource
```php
// Resource Configuration
- Table: drivers
- Model: App\Models\Driver
- Policy: App\Policies\DriverPolicy
- Form Fields: name, license_number, phone, email, status
- Table Columns: name, license, phone, status, current_assignment
- Filters: status, availability
- Actions: view, edit, assign_vehicle, view_schedule
- Widgets: driver_schedule_widget, performance_stats
```

### 5.4 MaintenanceResource
```php
// Resource Configuration
- Table: maintenance_records
- Model: App\Models\MaintenanceRecord
- Policy: App\Policies\MaintenancePolicy
- Form Fields: vehicle_id, service_type, cost, vendor, date, documents
- Table Columns: vehicle, service_type, cost, date, status
- Filters: date_range, service_type, vendor
- Actions: view, edit, upload_invoice, schedule_next
- File Upload: invoices, service_reports
```

---

## 6. Database Design

### 6.1 Core Tables

#### 6.1.1 Vehicles Table
```sql
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
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_types(id)
);
```

#### 6.1.2 Bookings Table (Polymorphic)
```sql
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
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id),
    INDEX idx_bookable (bookable_type, bookable_id),
    INDEX idx_dates (start_datetime, end_datetime)
);
```

#### 6.1.3 Vehicle Bookings Table
```sql
CREATE TABLE vehicle_bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vehicle_id BIGINT UNSIGNED NOT NULL,
    driver_id BIGINT UNSIGNED NULL,
    purpose ENUM('meeting', 'training', 'site_visit', 'audit', 'replacement_driver', 'shared_driver', 'goods_delivery', 'secretariat', 'krj_maintenance', 'others'),
    destination VARCHAR(255),
    passengers INT DEFAULT 1,
    fuel_card_id BIGINT UNSIGNED NULL,
    estimated_distance INT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    FOREIGN KEY (driver_id) REFERENCES drivers(id),
    FOREIGN KEY (fuel_card_id) REFERENCES fuel_cards(id)
);
```

#### 6.1.4 Drivers Table
```sql
CREATE TABLE drivers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    license_number VARCHAR(20) UNIQUE NOT NULL,
    license_expiry DATE NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    status ENUM('active', 'inactive', 'on_leave'),
    hire_date DATE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### 6.1.5 Maintenance Records Table
```sql
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

#### 6.1.6 Fuel Cards Table
```sql
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

### 6.2 Future Expansion Tables (Polymorphic)

#### 6.2.1 Facility Bookings Table (Future)
```sql
CREATE TABLE facility_bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    facility_id BIGINT UNSIGNED NOT NULL,
    setup_type ENUM('meeting', 'conference', 'training', 'event'),
    attendees INT,
    catering_required BOOLEAN DEFAULT FALSE,
    av_equipment TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (facility_id) REFERENCES facilities(id)
);
```

#### 6.2.2 Room Bookings Table (Future)
```sql
CREATE TABLE room_bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_id BIGINT UNSIGNED NOT NULL,
    meeting_type VARCHAR(100),
    participants INT,
    equipment_needed TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);
```

### 6.3 Lookup Tables

#### 6.3.1 Vehicle Types
```sql
CREATE TABLE vehicle_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### 6.3.2 Positions (Jawatan)
```sql
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

### 6.4 Database Relationships (Polymorphic)
- **SRS-DB-001**: Polymorphic: Bookings → Bookable (Vehicle, Facility, Room)
- **SRS-DB-002**: One-to-Many: User → Bookings
- **SRS-DB-003**: One-to-Many: Vehicle → Vehicle Bookings
- **SRS-DB-004**: One-to-Many: Driver → Vehicle Bookings
- **SRS-DB-005**: One-to-Many: Vehicle → Maintenance Records
- **SRS-DB-006**: One-to-One: Vehicle → Fuel Card
- **SRS-DB-007**: Many-to-Many: Users → Roles (via Spatie package)

---

## 7. User Interface Requirements

### 7.1 Filament Dashboard
- **SRS-UI-001**: Dashboard shall display key metrics widgets
- **SRS-UI-002**: Calendar widget shall show vehicle schedules
- **SRS-UI-003**: Statistics widgets shall show:
  - Total vehicles and availability
  - Pending bookings count
  - Maintenance due alerts
  - Fuel consumption trends

### 7.2 Filament Forms
- **SRS-UI-004**: All forms shall use Filament form components
- **SRS-UI-005**: Date/time pickers shall validate business hours
- **SRS-UI-006**: File uploads shall support PDF, images, and Excel files
- **SRS-UI-007**: Multi-step forms shall be used for complex bookings

### 7.3 Filament Tables
- **SRS-UI-008**: All tables shall support sorting, filtering, and searching
- **SRS-UI-009**: Bulk actions shall be available for mass operations
- **SRS-UI-010**: Export functionality shall generate Excel/PDF reports
- **SRS-UI-011**: Status columns shall use color-coded badges

### 7.4 Responsive Design
- **SRS-UI-012**: Interface shall be responsive for tablet and mobile devices
- **SRS-UI-013**: Filament's default responsive breakpoints shall be used
- **SRS-UI-014**: Mobile navigation shall collapse appropriately

---

## 8. Security Requirements

### 8.1 Authentication
- **SRS-SEC-001**: Filament authentication shall use Laravel's built-in auth
- **SRS-SEC-002**: Multi-factor authentication shall be optional for admin users
- **SRS-SEC-003**: Password policies shall enforce complexity requirements
- **SRS-SEC-004**: Session timeout shall be configurable (default 30 minutes)

### 8.2 Authorization
- **SRS-SEC-005**: Role-based access control using Spatie Laravel Permission
- **SRS-SEC-006**: Filament policies shall control resource access
- **SRS-SEC-007**: Department-based data isolation shall be enforced
- **SRS-SEC-008**: Audit trail shall log all CRUD operations

### 8.3 Data Protection
- **SRS-SEC-009**: Sensitive data shall be encrypted at rest
- **SRS-SEC-010**: HTTPS shall be enforced in production
- **SRS-SEC-011**: File uploads shall be validated and scanned
- **SRS-SEC-012**: Database backups shall be encrypted

---

## 9. Performance Requirements

### 9.1 Response Times
- **SRS-PERF-001**: Filament resource pages shall load within 2 seconds
- **SRS-PERF-002**: Form submissions shall process within 1 second
- **SRS-PERF-003**: Dashboard widgets shall load within 3 seconds
- **SRS-PERF-004**: Database queries shall execute within 500ms

### 9.2 Scalability
- **SRS-PERF-005**: System shall support 100 concurrent users
- **SRS-PERF-006**: Database shall handle 10,000+ vehicle records
- **SRS-PERF-007**: File storage shall support 1GB+ uploads
- **SRS-PERF-008**: Caching shall be implemented using Redis

### 9.3 Optimization
- **SRS-PERF-009**: Eloquent queries shall use eager loading to prevent N+1
- **SRS-PERF-010**: Filament tables shall implement pagination
- **SRS-PERF-011**: Large datasets shall use lazy loading
- **SRS-PERF-012**: Static assets shall be cached and compressed

---

## 10. Integration Requirements

### 10.1 External Systems
- **SRS-INT-001**: Fuel vendor API integration for usage data
- **SRS-INT-002**: Email service integration for notifications
- **SRS-INT-003**: SMS gateway for urgent alerts
- **SRS-INT-004**: Government SSO integration (optional)

### 10.2 Data Import/Export
- **SRS-INT-005**: Excel import for bulk vehicle registration
- **SRS-INT-006**: CSV export for all data tables
- **SRS-INT-007**: PDF generation for reports and documents
- **SRS-INT-008**: Backup/restore functionality

---

## 11. Testing Requirements

### 11.1 Unit Testing
- **SRS-TEST-001**: PHPUnit tests for all models and services
- **SRS-TEST-002**: Feature tests for Filament resources
- **SRS-TEST-003**: Test coverage shall exceed 80%
- **SRS-TEST-004**: Database factories for test data generation

### 11.2 Integration Testing
- **SRS-TEST-005**: Filament resource functionality testing
- **SRS-TEST-006**: Authentication and authorization testing
- **SRS-TEST-007**: File upload and processing testing
- **SRS-TEST-008**: Email and notification testing

### 11.3 Performance Testing
- **SRS-TEST-009**: Load testing with 100 concurrent users
- **SRS-TEST-010**: Database performance testing
- **SRS-TEST-011**: Memory usage optimization testing
- **SRS-TEST-012**: Browser compatibility testing

---

## 12. Deployment Requirements

### 12.1 Server Requirements
- **SRS-DEPLOY-001**: PHP 8.3+ with required extensions
- **SRS-DEPLOY-002**: MySQL 8.0+ database server
- **SRS-DEPLOY-003**: Redis for caching and sessions
- **SRS-DEPLOY-004**: Web server (Apache/Nginx) with SSL

### 12.2 Environment Configuration
- **SRS-DEPLOY-005**: Environment-specific .env files
- **SRS-DEPLOY-006**: Database migrations and seeders
- **SRS-DEPLOY-007**: File storage configuration (local/S3)
- **SRS-DEPLOY-008**: Queue worker configuration

### 12.3 Monitoring
- **SRS-DEPLOY-009**: Application logging using Laravel Log
- **SRS-DEPLOY-010**: Error tracking and reporting
- **SRS-DEPLOY-011**: Performance monitoring
- **SRS-DEPLOY-012**: Backup verification and testing

---

## 13. Maintenance Requirements

### 13.1 Code Maintenance
- **SRS-MAINT-001**: Code shall follow PSR-12 coding standards
- **SRS-MAINT-002**: Documentation shall be maintained in code comments
- **SRS-MAINT-003**: Version control using Git with proper branching
- **SRS-MAINT-004**: Regular dependency updates and security patches

### 13.2 Data Maintenance
- **SRS-MAINT-005**: Daily automated database backups
- **SRS-MAINT-006**: Log rotation and cleanup procedures
- **SRS-MAINT-007**: Data archival for old records
- **SRS-MAINT-008**: Performance monitoring and optimization

### 13.3 System Maintenance
- **SRS-MAINT-009**: Regular security updates and patches
- **SRS-MAINT-010**: Performance tuning and optimization
- **SRS-MAINT-011**: Capacity planning and scaling
- **SRS-MAINT-012**: Disaster recovery procedures

---

## Appendices

### Appendix A: Filament Package Requirements
```json
{
    "require": {
        "filament/filament": "^3.0",
        "filament/forms": "^3.0",
        "filament/tables": "^3.0",
        "filament/notifications": "^3.0",
        "filament/widgets": "^3.0",
        "spatie/laravel-permission": "^6.0",
        "laravel/sanctum": "^4.0"
    }
}
```

### Appendix B: Database Indexes
```sql
-- Performance optimization indexes
CREATE INDEX idx_bookings_dates ON bookings(start_datetime, end_datetime);
CREATE INDEX idx_bookings_status ON bookings(status);
CREATE INDEX idx_vehicles_status ON vehicles(status);
CREATE INDEX idx_maintenance_vehicle_date ON maintenance_records(vehicle_id, service_date);
```

### Appendix C: Filament Configuration
```php
// config/filament.php key configurations
'auth' => [
    'guard' => 'web',
    'pages' => [
        'login' => \Filament\Http\Livewire\Auth\Login::class,
    ],
],
'middleware' => [
    'auth' => [
        Authenticate::class,
    ],
    'base' => [
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        AuthenticateSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
        SubstituteBindings::class,
        DisableBladeIconComponents::class,
        DispatchServingFilamentEvent::class,
    ],
],
```

---

**Document End**

*This Software Requirements Specification provides comprehensive technical specifications for implementing the Fleet Management System using Laravel Filament framework, ensuring KRISA compliance and government standards.*
