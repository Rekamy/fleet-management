# Tech Context - Fleet Management System

## Technology Stack

### Backend Framework
- **Laravel 11.x**: Latest LTS version with modern features
- **PHP 8.3+**: Required for Laravel 11 compatibility
- **Composer**: Dependency management and autoloading

### Admin Panel Framework
- **Filament 3.x**: Modern admin panel with Livewire integration
- **Livewire 3.x**: Reactive components without JavaScript complexity
- **Alpine.js**: Minimal JavaScript framework for interactions
- **Tailwind CSS**: Utility-first CSS framework (via Filament)

### Database & Storage
- **MySQL 8.0**: Primary database with JSON support
- **Redis**: Caching and session storage
- **Local/S3**: File storage for documents and images

### Authentication & Authorization
- **Laravel Auth**: Built-in authentication system
- **Spatie Laravel Permission**: Role and permission management
- **Laravel Sanctum**: API token authentication (future mobile app)

## Development Setup

### Local Environment Requirements
```bash
# Required software
PHP 8.3+
Composer 2.x
Node.js 18+ (for asset compilation)
MySQL 8.0+
Redis 6+

# PHP Extensions
php-mysql
php-redis
php-gd
php-zip
php-xml
php-mbstring
php-curl
```

### Installation Process
```bash
# Clone and setup
git clone <repository>
cd fleet-management
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Filament setup
php artisan make:filament-user

# Development server
php artisan serve
npm run dev
```

### Development Tools
- **Laravel Telescope**: Debugging and monitoring
- **Laravel Debugbar**: Development debugging
- **PHPUnit**: Testing framework
- **Pest**: Modern testing framework (alternative)
- **Laravel Pint**: Code formatting (PSR-12)

## Technical Constraints

### Performance Requirements
- **Page Load**: <2 seconds for resource pages
- **Form Submission**: <1 second processing time
- **Dashboard**: <3 seconds with all widgets
- **Database Queries**: <500ms execution time
- **Concurrent Users**: Support for 100 simultaneous users

### Browser Compatibility
- **Modern Browsers**: Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
- **Mobile Support**: Responsive design for tablets and phones
- **JavaScript**: ES6+ features (via Alpine.js)

### Security Requirements
- **HTTPS**: Enforced in production
- **CSRF Protection**: Laravel built-in protection
- **XSS Prevention**: Blade template escaping
- **SQL Injection**: Eloquent ORM protection
- **File Upload**: Validation and scanning

### Scalability Constraints
- **Database**: Single MySQL instance initially
- **File Storage**: Local storage initially, S3 for production
- **Caching**: Single Redis instance
- **Queue Workers**: Single worker initially

## Dependencies

### Core Laravel Packages
```json
{
  "laravel/framework": "^11.0",
  "laravel/sanctum": "^4.0",
  "laravel/tinker": "^2.9"
}
```

### Filament Packages
```json
{
  "filament/filament": "^3.0",
  "filament/forms": "^3.0",
  "filament/tables": "^3.0",
  "filament/notifications": "^3.0",
  "filament/widgets": "^3.0"
}
```

### Additional Packages
```json
{
  "spatie/laravel-permission": "^6.0",
  "filament/spatie-laravel-settings-plugin": "^3.0",
  "filament/spatie-laravel-media-library-plugin": "^3.0",
  "maatwebsite/excel": "^3.1",
  "barryvdh/laravel-dompdf": "^2.0"
}
```

### Development Dependencies
```json
{
  "laravel/telescope": "^5.0",
  "barryvdh/laravel-debugbar": "^3.9",
  "fakerphp/faker": "^1.23",
  "laravel/pint": "^1.13",
  "nunomaduro/collision": "^8.0",
  "phpunit/phpunit": "^11.0"
}
```

## Tool Usage Patterns

### Filament Resource Generation
```bash
# Generate complete resource with pages
php artisan make:filament-resource Vehicle --generate

# Generate simple resource
php artisan make:filament-resource VehicleType --simple

# Generate custom page
php artisan make:filament-page Calendar
```

### Database Management
```bash
# Create migration
php artisan make:migration create_bookings_table

# Create model with migration and factory
php artisan make:model Booking -mf

# Create seeder
php artisan make:seeder VehicleSeeder

# Run migrations
php artisan migrate --seed
```

### Testing Commands
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter BookingTest

# Generate test coverage
php artisan test --coverage
```

### Code Quality
```bash
# Format code (PSR-12)
./vendor/bin/pint

# Static analysis
./vendor/bin/phpstan analyse

# Security check
composer audit
```

## Configuration Patterns

### Environment Variables
```env
# Application
APP_NAME="Fleet Management"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://fleet.example.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fleet_management
DB_USERNAME=fleet_user
DB_PASSWORD=secure_password

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=fleet@example.com
MAIL_PASSWORD=mail_password
```

### Filament Configuration
```php
// config/filament.php
return [
    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),
    'assets_path' => null,
    'cache_path' => base_path('bootstrap/cache/filament'),
    'livewire_loading_delay' => 'default',
];
```

### Database Configuration
```php
// config/database.php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'fleet_management'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'strict' => true,
    'engine' => null,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ]) : [],
],
```

## Integration Points

### External APIs
- **Fuel Vendor APIs**: RESTful integration for usage data
- **Email Service**: SMTP or API-based email delivery
- **SMS Gateway**: API integration for urgent notifications
- **Government SSO**: SAML or OAuth integration (optional)

### File Handling
- **Upload Validation**: File type, size, and content validation
- **Storage**: Local development, S3 production
- **Processing**: Image resizing, PDF generation
- **Security**: Virus scanning, access control

### Monitoring & Logging
- **Application Logs**: Laravel Log facade
- **Error Tracking**: Sentry or similar service
- **Performance Monitoring**: New Relic or similar
- **Uptime Monitoring**: External service monitoring

## Deployment Architecture

### Production Environment
```
Load Balancer (Nginx)
├── Web Server (Nginx + PHP-FPM)
├── Application Server (Laravel)
├── Database Server (MySQL 8.0)
├── Cache Server (Redis)
└── File Storage (S3 or NFS)
```

### CI/CD Pipeline
```
Git Push → GitHub Actions → Tests → Build → Deploy → Health Check
```

### Backup Strategy
- **Database**: Daily automated backups with 30-day retention
- **Files**: Incremental backups to S3
- **Code**: Git repository with tags for releases
- **Configuration**: Environment files in secure storage

## Future Technical Considerations

### Mobile API
- **Laravel Sanctum**: Token-based authentication
- **API Resources**: Structured JSON responses
- **Rate Limiting**: API throttling and abuse prevention

### Microservices Migration
- **Service Boundaries**: Booking, Vehicle, User services
- **Communication**: HTTP APIs or message queues
- **Data Consistency**: Event sourcing patterns

### Advanced Features
- **Real-time Updates**: WebSocket integration
- **Machine Learning**: Usage prediction and optimization
- **IoT Integration**: Vehicle tracking and telemetry
