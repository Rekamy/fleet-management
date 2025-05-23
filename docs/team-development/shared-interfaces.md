# Shared Interfaces & Contracts

## Overview
This document defines all shared interfaces, contracts, and integration points between the three development teams. These interfaces ensure loose coupling while maintaining system cohesion.

---

## Core Interfaces

### 🔐 Authentication & Authorization Interfaces
**Owner**: Team 1  
**Consumers**: Team 2, Team 3

```php
<?php

namespace App\Contracts\Auth;

interface AuthenticationServiceInterface
{
    /**
     * Get the currently authenticated user
     */
    public function getCurrentUser(): ?User;
    
    /**
     * Check if user has specific permission
     */
    public function checkPermission(string $permission): bool;
    
    /**
     * Get user's department ID
     */
    public function getUserDepartment(): ?int;
    
    /**
     * Check if user belongs to specific department
     */
    public function belongsToDepartment(int $departmentId): bool;
    
    /**
     * Get user's role names
     */
    public function getUserRoles(): array;
    
    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool;
}

interface AuthorizationServiceInterface
{
    /**
     * Check if user can perform action on resource
     */
    public function authorize(string $action, $resource): bool;
    
    /**
     * Get filtered query based on user permissions
     */
    public function filterQuery($query, string $model): mixed;
    
    /**
     * Check if user can access department data
     */
    public function canAccessDepartment(int $departmentId): bool;
}

interface UserServiceInterface
{
    /**
     * Get user by ID with relationships
     */
    public function getUserById(int $id): ?User;
    
    /**
     * Get users by department
     */
    public function getUsersByDepartment(int $departmentId): Collection;
    
    /**
     * Get users with specific role
     */
    public function getUsersByRole(string $role): Collection;
    
    /**
     * Update user profile
     */
    public function updateProfile(int $userId, array $data): bool;
}
```

---

## Vehicle & Booking Interfaces
**Owner**: Team 2  
**Consumers**: Team 1, Team 3

```php
<?php

namespace App\Contracts\Fleet;

interface BookableInterface
{
    /**
     * Get all bookings for this resource
     */
    public function bookings();
    
    /**
     * Check if resource is available for given period
     */
    public function isAvailable(Carbon $startDate, Carbon $endDate): bool;
    
    /**
     * Get booking validation rules for this resource
     */
    public function getBookingValidationRules(): array;
    
    /**
     * Get resource capacity/limits
     */
    public function getBookingCapacity(): int;
    
    /**
     * Get resource booking constraints
     */
    public function getBookingConstraints(): array;
}

interface VehicleServiceInterface
{
    /**
     * Get vehicle by ID with relationships
     */
    public function getVehicle(int $id): ?Vehicle;
    
    /**
     * Get available vehicles for period
     */
    public function getAvailableVehicles(Carbon $startDate, Carbon $endDate): Collection;
    
    /**
     * Update vehicle status
     */
    public function updateVehicleStatus(int $vehicleId, string $status): bool;
    
    /**
     * Get vehicle bookings
     */
    public function getVehicleBookings(int $vehicleId, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection;
    
    /**
     * Check vehicle availability
     */
    public function checkAvailability(int $vehicleId, Carbon $startDate, Carbon $endDate): bool;
    
    /**
     * Get vehicle maintenance schedule
     */
    public function getMaintenanceSchedule(int $vehicleId): Collection;
}

interface BookingServiceInterface
{
    /**
     * Create new booking
     */
    public function createBooking(array $data): Booking;
    
    /**
     * Update existing booking
     */
    public function updateBooking(int $bookingId, array $data): bool;
    
    /**
     * Cancel booking
     */
    public function cancelBooking(int $bookingId, string $reason = null): bool;
    
    /**
     * Approve booking
     */
    public function approveBooking(int $bookingId, int $approverId): bool;
    
    /**
     * Reject booking
     */
    public function rejectBooking(int $bookingId, int $approverId, string $reason): bool;
    
    /**
     * Check for booking conflicts
     */
    public function checkConflicts(string $bookableType, int $bookableId, Carbon $startDate, Carbon $endDate, ?int $excludeBookingId = null): Collection;
    
    /**
     * Get bookings for user
     */
    public function getUserBookings(int $userId, ?string $status = null): Collection;
    
    /**
     * Get pending approvals for user
     */
    public function getPendingApprovals(int $userId): Collection;
}

interface CalendarServiceInterface
{
    /**
     * Get calendar events for date range
     */
    public function getEvents(Carbon $startDate, Carbon $endDate, ?array $filters = null): Collection;
    
    /**
     * Create calendar event from booking
     */
    public function createEventFromBooking(Booking $booking): array;
    
    /**
     * Update calendar event
     */
    public function updateEvent(int $bookingId, array $data): bool;
    
    /**
     * Delete calendar event
     */
    public function deleteEvent(int $bookingId): bool;
    
    /**
     * Get conflicts for time slot
     */
    public function getConflicts(Carbon $startDate, Carbon $endDate, ?string $resourceType = null): Collection;
}

interface DriverServiceInterface
{
    /**
     * Get available drivers for period
     */
    public function getAvailableDrivers(Carbon $startDate, Carbon $endDate): Collection;
    
    /**
     * Assign driver to booking
     */
    public function assignDriver(int $bookingId, int $driverId): bool;
    
    /**
     * Get driver schedule
     */
    public function getDriverSchedule(int $driverId, Carbon $date): Collection;
    
    /**
     * Check driver availability
     */
    public function checkDriverAvailability(int $driverId, Carbon $startDate, Carbon $endDate): bool;
}
```

---

## Maintenance & Administration Interfaces
**Owner**: Team 3  
**Consumers**: Team 1, Team 2

```php
<?php

namespace App\Contracts\Maintenance;

interface MaintenanceServiceInterface
{
    /**
     * Schedule maintenance for vehicle
     */
    public function scheduleMaintenance(int $vehicleId, Carbon $date, string $type, array $details): MaintenanceRecord;
    
    /**
     * Update maintenance status
     */
    public function updateMaintenanceStatus(int $maintenanceId, string $status): bool;
    
    /**
     * Get vehicle maintenance history
     */
    public function getMaintenanceHistory(int $vehicleId): Collection;
    
    /**
     * Get upcoming maintenance
     */
    public function getUpcomingMaintenance(?int $vehicleId = null): Collection;
    
    /**
     * Calculate next maintenance date
     */
    public function calculateNextMaintenance(int $vehicleId): ?Carbon;
    
    /**
     * Check if vehicle is under maintenance
     */
    public function isVehicleUnderMaintenance(int $vehicleId): bool;
    
    /**
     * Get maintenance cost summary
     */
    public function getMaintenanceCosts(int $vehicleId, ?Carbon $startDate = null, ?Carbon $endDate = null): array;
}

interface FuelManagementServiceInterface
{
    /**
     * Get fuel card for vehicle
     */
    public function getFuelCard(int $vehicleId): ?FuelCard;
    
    /**
     * Record fuel usage
     */
    public function recordUsage(int $fuelCardId, float $amount, float $cost, Carbon $date): FuelUsage;
    
    /**
     * Check fuel limit
     */
    public function checkFuelLimit(int $fuelCardId, float $amount): bool;
    
    /**
     * Get monthly usage
     */
    public function getMonthlyUsage(int $fuelCardId, ?Carbon $month = null): float;
    
    /**
     * Get fuel usage history
     */
    public function getUsageHistory(int $fuelCardId, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection;
    
    /**
     * Update fuel card limit
     */
    public function updateFuelLimit(int $fuelCardId, float $limit): bool;
}

interface LookupServiceInterface
{
    /**
     * Get all vehicle types
     */
    public function getVehicleTypes(): Collection;
    
    /**
     * Get all positions
     */
    public function getPositions(): Collection;
    
    /**
     * Get KRJ eligible positions
     */
    public function getKRJEligiblePositions(): Collection;
    
    /**
     * Get manufacturers
     */
    public function getManufacturers(): Collection;
    
    /**
     * Get vehicle models by manufacturer
     */
    public function getVehicleModels(?int $manufacturerId = null): Collection;
    
    /**
     * Get companies
     */
    public function getCompanies(): Collection;
    
    /**
     * Get locations
     */
    public function getLocations(): Collection;
    
    /**
     * Cache lookup data
     */
    public function cacheLookupData(string $type): void;
    
    /**
     * Clear lookup cache
     */
    public function clearLookupCache(?string $type = null): void;
}

interface ReportingServiceInterface
{
    /**
     * Generate report
     */
    public function generateReport(string $type, array $parameters): array;
    
    /**
     * Export data to format
     */
    public function exportData(string $model, string $format, ?array $filters = null): string;
    
    /**
     * Schedule report generation
     */
    public function scheduleReport(string $type, array $parameters, string $schedule, array $recipients): bool;
    
    /**
     * Get available report types
     */
    public function getAvailableReports(): array;
    
    /**
     * Get report history
     */
    public function getReportHistory(?int $userId = null): Collection;
}
```

---

## Event Interfaces
**Shared by all teams**

```php
<?php

namespace App\Contracts\Events;

interface NotificationInterface
{
    /**
     * Send notification
     */
    public function send(array $recipients, string $message, ?array $data = null): bool;
    
    /**
     * Send email notification
     */
    public function sendEmail(array $recipients, string $subject, string $message, ?array $attachments = null): bool;
    
    /**
     * Send SMS notification
     */
    public function sendSMS(array $recipients, string $message): bool;
    
    /**
     * Get notification preferences
     */
    public function getPreferences(int $userId): array;
    
    /**
     * Update notification preferences
     */
    public function updatePreferences(int $userId, array $preferences): bool;
}

interface AuditServiceInterface
{
    /**
     * Log activity
     */
    public function logActivity(string $action, string $model, int $modelId, ?array $changes = null): void;
    
    /**
     * Get audit trail
     */
    public function getAuditTrail(string $model, int $modelId): Collection;
    
    /**
     * Get user activity
     */
    public function getUserActivity(int $userId, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection;
    
    /**
     * Get system activity
     */
    public function getSystemActivity(?Carbon $startDate = null, ?Carbon $endDate = null): Collection;
}
```

---

## Data Transfer Objects (DTOs)

### Booking DTOs
```php
<?php

namespace App\DTOs\Booking;

class CreateBookingDTO
{
    public function __construct(
        public int $userId,
        public string $bookableType,
        public int $bookableId,
        public Carbon $startDateTime,
        public Carbon $endDateTime,
        public string $purpose,
        public ?string $destination = null,
        public ?int $passengers = null,
        public ?string $specialRequirements = null,
        public ?int $driverId = null
    ) {}
    
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'bookable_type' => $this->bookableType,
            'bookable_id' => $this->bookableId,
            'start_datetime' => $this->startDateTime,
            'end_datetime' => $this->endDateTime,
            'purpose' => $this->purpose,
            'destination' => $this->destination,
            'passengers' => $this->passengers,
            'special_requirements' => $this->specialRequirements,
            'driver_id' => $this->driverId,
        ];
    }
}

class BookingConflictDTO
{
    public function __construct(
        public int $bookingId,
        public string $bookableType,
        public int $bookableId,
        public Carbon $startDateTime,
        public Carbon $endDateTime,
        public string $status,
        public string $conflictType
    ) {}
}
```

### Vehicle DTOs
```php
<?php

namespace App\DTOs\Vehicle;

class VehicleAvailabilityDTO
{
    public function __construct(
        public int $vehicleId,
        public string $registrationNumber,
        public string $make,
        public string $model,
        public bool $isAvailable,
        public ?string $unavailableReason = null,
        public ?Carbon $nextAvailable = null
    ) {}
}

class MaintenanceScheduleDTO
{
    public function __construct(
        public int $vehicleId,
        public string $serviceType,
        public Carbon $scheduledDate,
        public ?Carbon $nextServiceDate = null,
        public string $status,
        public ?float $estimatedCost = null
    ) {}
}
```

---

## Event Classes

### Booking Events
```php
<?php

namespace App\Events\Booking;

class BookingCreated
{
    public function __construct(
        public Booking $booking,
        public User $user
    ) {}
}

class BookingApproved
{
    public function __construct(
        public Booking $booking,
        public User $approver
    ) {}
}

class BookingRejected
{
    public function __construct(
        public Booking $booking,
        public User $approver,
        public string $reason
    ) {}
}

class BookingCancelled
{
    public function __construct(
        public Booking $booking,
        public User $cancelledBy,
        public ?string $reason = null
    ) {}
}

class BookingModified
{
    public function __construct(
        public Booking $booking,
        public array $changes,
        public User $modifiedBy
    ) {}
}
```

### Vehicle Events
```php
<?php

namespace App\Events\Vehicle;

class VehicleStatusChanged
{
    public function __construct(
        public Vehicle $vehicle,
        public string $oldStatus,
        public string $newStatus,
        public ?User $changedBy = null
    ) {}
}

class MaintenanceScheduled
{
    public function __construct(
        public Vehicle $vehicle,
        public MaintenanceRecord $maintenance,
        public User $scheduledBy
    ) {}
}

class MaintenanceCompleted
{
    public function __construct(
        public Vehicle $vehicle,
        public MaintenanceRecord $maintenance
    ) {}
}
```

---

## Exception Classes

### Booking Exceptions
```php
<?php

namespace App\Exceptions\Booking;

class BookingConflictException extends Exception
{
    public function __construct(
        public array $conflicts,
        string $message = 'Booking conflicts detected'
    ) {
        parent::__construct($message);
    }
}

class BookingNotAvailableException extends Exception
{
    public function __construct(
        public string $reason,
        string $message = 'Resource not available for booking'
    ) {
        parent::__construct($message);
    }
}

class UnauthorizedBookingException extends Exception
{
    public function __construct(
        string $message = 'User not authorized to create this booking'
    ) {
        parent::__construct($message);
    }
}
```

### Vehicle Exceptions
```php
<?php

namespace App\Exceptions\Vehicle;

class VehicleNotAvailableException extends Exception
{
    public function __construct(
        public int $vehicleId,
        public string $reason,
        string $message = 'Vehicle not available'
    ) {
        parent::__construct($message);
    }
}

class VehicleUnderMaintenanceException extends Exception
{
    public function __construct(
        public int $vehicleId,
        public ?Carbon $availableDate = null,
        string $message = 'Vehicle is under maintenance'
    ) {
        parent::__construct($message);
    }
}
```

### Fuel Exceptions
```php
<?php

namespace App\Exceptions\Fuel;

class FuelLimitExceededException extends Exception
{
    public function __construct(
        public float $currentUsage,
        public float $limit,
        public float $requestedAmount,
        string $message = 'Fuel limit exceeded'
    ) {
        parent::__construct($message);
    }
}
```

---

## Service Provider Bindings

### Interface Bindings
```php
<?php

namespace App\Providers;

class InterfaceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Authentication Services (Team 1)
        $this->app->bind(
            AuthenticationServiceInterface::class,
            AuthenticationService::class
        );
        
        $this->app->bind(
            AuthorizationServiceInterface::class,
            AuthorizationService::class
        );
        
        $this->app->bind(
            UserServiceInterface::class,
            UserService::class
        );
        
        // Vehicle Services (Team 2)
        $this->app->bind(
            VehicleServiceInterface::class,
            VehicleService::class
        );
        
        $this->app->bind(
            BookingServiceInterface::class,
            BookingService::class
        );
        
        $this->app->bind(
            CalendarServiceInterface::class,
            CalendarService::class
        );
        
        $this->app->bind(
            DriverServiceInterface::class,
            DriverService::class
        );
        
        // Maintenance Services (Team 3)
        $this->app->bind(
            MaintenanceServiceInterface::class,
            MaintenanceService::class
        );
        
        $this->app->bind(
            FuelManagementServiceInterface::class,
            FuelManagementService::class
        );
        
        $this->app->bind(
            LookupServiceInterface::class,
            LookupService::class
        );
        
        $this->app->bind(
            ReportingServiceInterface::class,
            ReportingService::class
        );
        
        // Shared Services
        $this->app->bind(
            NotificationInterface::class,
            NotificationService::class
        );
        
        $this->app->bind(
            AuditServiceInterface::class,
            AuditService::class
        );
    }
}
```

---

## API Response Standards

### Standard Response Format
```php
<?php

namespace App\Http\Responses;

class ApiResponse
{
    public static function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString()
        ], $code);
    }
    
    public static function error(string $message, $errors = null, int $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->toISOString()
        ], $code);
    }
    
    public static function paginated($data, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ],
            'timestamp' => now()->toISOString()
        ]);
    }
}
```

---

## Integration Testing Contracts

### Test Interface Implementations
```php
<?php

namespace Tests\Contracts;

class MockAuthenticationService implements AuthenticationServiceInterface
{
    private ?User $currentUser = null;
    private array $permissions = [];
    private array $roles = [];
    
    public function setCurrentUser(?User $user): void
    {
        $this->currentUser = $user;
    }
    
    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }
    
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
    
    public function getCurrentUser(): ?User
    {
        return $this->currentUser;
    }
    
    public function checkPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions);
    }
    
    public function getUserDepartment(): ?int
    {
        return $this->currentUser?->department_id;
    }
    
    public function belongsToDepartment(int $departmentId): bool
    {
        return $this->currentUser?->department_id === $departmentId;
    }
    
    public function getUserRoles(): array
    {
        return $this->roles;
    }
    
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }
}
```

---

## Documentation Standards

### Interface Documentation Template
```php
<?php

/**
 * Service Interface for [Service Name]
 * 
 * @package App\Contracts\[Package]
 * @author Team [Number]
 * @version 1.0
 * @since 2025-05-23
 * 
 * This interface defines the contract for [service description].
 * 
 * @example
 * ```php
 * $service = app(ServiceInterface::class);
 * $result = $service->method($parameter);
 * ```
 */
interface ServiceInterface
{
    /**
     * Method description
     * 
     * @param Type $parameter Parameter description
     * @return Type Return description
     * @throws ExceptionType When condition occurs
     * 
     * @example
     * ```php
     * $result = $service->method($parameter);
     * ```
     */
    public function method($parameter): Type;
}
```

---

**Document Version**: 1.0  
**Last Updated**: [Current Date]  
**Next Review**: Weekly during development  
**Owner**: Technical Architect  
**Reviewers**: All Team Leads
