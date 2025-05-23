# Business Requirements Specification (BRS)
## Fleet Management System

### Document Information
- **Document Version**: 1.0
- **Date**: May 2025
- **Project**: Fleet Management System
- **Document Type**: Business Requirements Specification

---

## Table of Contents
1. [Executive Summary](#1-executive-summary)
2. [Project Overview](#2-project-overview)
3. [Stakeholder Analysis](#3-stakeholder-analysis)
4. [Functional Requirements](#4-functional-requirements)
5. [Non-Functional Requirements](#5-non-functional-requirements)
6. [Business Rules](#6-business-rules)
7. [Data Requirements](#7-data-requirements)
8. [Integration Requirements](#8-integration-requirements)
9. [Security Requirements](#9-security-requirements)
10. [Implementation Phases](#10-implementation-phases)
11. [Assumptions and Constraints](#11-assumptions-and-constraints)
12. [Acceptance Criteria](#12-acceptance-criteria)

---

## 1. Executive Summary

The Fleet Management System is designed to streamline vehicle operations, bookings, maintenance, and administrative tasks for organizational fleet management. The system comprises three core modules: Vehicle Management, User Management, and Administration, providing comprehensive fleet oversight and operational efficiency.

### 1.1 Business Objectives
- Automate vehicle booking and allocation processes
- Track vehicle maintenance and service history
- Manage fuel card allocation and usage
- Oversee official vehicle (KRJ) assignments
- Provide centralized fleet administration

### 1.2 Key Benefits
- Improved operational efficiency
- Better resource utilization
- Enhanced tracking and reporting
- Streamlined approval processes
- Cost management and control

---

## 2. Project Overview

### 2.1 System Purpose
The Fleet Management System will serve as a centralized platform for managing organizational vehicles, drivers, bookings, and related administrative functions.

### 2.2 System Scope
The system covers:
- Vehicle inventory management
- Booking and reservation system
- Maintenance scheduling and tracking
- Fuel card management
- User access control
- Administrative lookup data management

---

## 3. Stakeholder Analysis

### 3.1 Primary Users
- **Fleet Administrators**: Full system access and management
- **Fleet Managers**: Vehicle allocation and oversight
- **Drivers**: Schedule viewing and basic updates
- **Regular Users**: Vehicle booking requests
- **Maintenance Staff**: Service record management

### 3.2 User Roles and Permissions
- **Super Admin**: Complete system control
- **Fleet Manager**: Vehicle and booking management
- **User**: Booking requests and viewing
- **Driver**: Schedule access and updates
- **Maintenance**: Service record management

---

## 4. Functional Requirements

### 4.1 Module 1: Vehicle Management (Modul Pengurusan Kenderaan)

#### 4.1.1 Vehicle Booking (Tempahan Kenderaan)
**Purpose**: Enable users to request and book vehicles for various organizational purposes.

**Functional Requirements**:
- **FR-VB-001**: System shall allow users to submit vehicle booking requests
- **FR-VB-002**: System shall support booking for the following purposes:
  - Mesyuarat (Meetings)
  - Kursus/Latihan (Courses/Training)
  - Lawatan Tapak (Site Visits)
  - Pengauditan (Auditing)
  - Pemandu Ganti (Replacement Driver)
  - Pemandu Gunasama (Shared Driver for JUSA C with KRJ)
  - Penghantaran Barang (Goods Delivery)
  - Urusetia Mesyuarat/Kursus/Latihan/Majlis (Secretariat duties)
  - Penyelenggaraan KRJ/Kereta Jawatan (KRJ/Official Vehicle Maintenance)
  - Lain-lain (Others - with notes)
- **FR-VB-003**: System shall validate booking requests against vehicle availability
- **FR-VB-004**: System shall support booking approval workflow
- **FR-VB-005**: System shall generate booking confirmations and notifications
- **FR-VB-006**: System shall allow booking modifications and cancellations
- **FR-VB-007**: System shall track booking history and status

#### 4.1.2 Vehicle Maintenance (Penyelenggaraan Kereta)
**Purpose**: Manage vehicle maintenance schedules, records, and service history.

**Functional Requirements**:
- **FR-VM-001**: System shall maintain vehicle service history records
- **FR-VM-002**: System shall schedule preventive maintenance
- **FR-VM-003**: System shall track maintenance costs and vendors
- **FR-VM-004**: System shall generate maintenance alerts and reminders
- **FR-VM-005**: System shall manage maintenance documentation
- **FR-VM-006**: System shall update vehicle availability based on maintenance status

#### 4.1.3 Official Position Vehicles - KRJ (Kenderaan Kereta Rasmi Jawatan)
**Purpose**: Manage vehicles assigned to specific official positions.

**Functional Requirements**:
- **FR-KRJ-001**: System shall assign vehicles to specific positions/ranks
- **FR-KRJ-002**: System shall track KRJ usage and mileage
- **FR-KRJ-003**: System shall manage KRJ maintenance schedules
- **FR-KRJ-004**: System shall handle KRJ transfer between positions
- **FR-KRJ-005**: System shall generate KRJ usage reports

#### 4.1.4 Fuel Card Management (Kad Minyak)
**Purpose**: Manage fuel card allocation, usage tracking, and cost control.

**Functional Requirements**:
- **FR-FC-001**: System shall assign fuel cards to vehicles/drivers
- **FR-FC-002**: System shall track fuel consumption and costs
- **FR-FC-003**: System shall set fuel card limits and restrictions
- **FR-FC-004**: System shall generate fuel usage reports
- **FR-FC-005**: System shall manage fuel card renewals and replacements
- **FR-FC-006**: System shall integrate with fuel vendor systems (if applicable)

### 4.2 Module 2: User Management (Modul Pengguna)

#### 4.2.1 User Administration
**Purpose**: Manage system users and their access rights.

**Functional Requirements**:
- **FR-UA-001**: System shall support user creation, update, and deletion (CRUD)
- **FR-UA-002**: System shall implement user impersonation for administrative purposes
- **FR-UA-003**: System shall manage user profiles and contact information
- **FR-UA-004**: System shall track user activity and login history

#### 4.2.2 Access Control (Kebenaran Capaian)
**Purpose**: Control user access to system features and data.

**Functional Requirements**:
- **FR-AC-001**: System shall implement role-based access control
- **FR-AC-002**: System shall define permission levels for different user types
- **FR-AC-003**: System shall restrict access based on organizational hierarchy
- **FR-AC-004**: System shall log access attempts and security events

#### 4.2.3 First-Time User Setup (Pengguna Kali Pertama)
**Purpose**: Facilitate new user onboarding and initial setup.

**Functional Requirements**:
- **FR-FU-001**: System shall provide guided first-time user setup
- **FR-FU-002**: System shall require initial password change
- **FR-FU-003**: System shall collect necessary user profile information
- **FR-FU-004**: System shall assign default permissions based on user role

#### 4.2.4 Password Management (Lupa Kata Laluan)
**Purpose**: Handle password recovery and security.

**Functional Requirements**:
- **FR-PM-001**: System shall provide password reset functionality
- **FR-PM-002**: System shall implement secure password recovery process
- **FR-PM-003**: System shall enforce password complexity requirements
- **FR-PM-004**: System shall implement password expiration policies

### 4.3 Module 3: Administration (Modul Pentadbiran)

#### 4.3.1 Lookup Data Management (CRUD)
**Purpose**: Manage reference data and lookup tables.

**Functional Requirements**:
- **FR-LD-001**: System shall manage the following lookup tables:
  - Jawatan (Positions)
  - Status Permohonan (Application Status)
  - Pembuat Kereta (Car Manufacturers)
  - Syarikat (Companies)
  - Model Kenderaan (Vehicle Models)
  - Kategori Kenderaan (Vehicle Categories)
  - Kategori Jenis Kenderaan (Vehicle Type Categories)
  - Lokasi Menunggu (Waiting Locations)
  - Jenis Kenderaan (Vehicle Types - trucks, buses, etc.)
- **FR-LD-002**: System shall provide CRUD operations for all lookup data
- **FR-LD-003**: System shall maintain data integrity and relationships
- **FR-LD-004**: System shall support data import/export functionality

#### 4.3.2 Vehicle Registration and Updates
**Purpose**: Manage vehicle inventory and information.

**Functional Requirements**:
- **FR-VR-001**: System shall register new vehicles with complete specifications
- **FR-VR-002**: System shall update vehicle information and status
- **FR-VR-003**: System shall maintain vehicle documentation and certificates
- **FR-VR-004**: System shall track vehicle ownership and transfer history
- **FR-VR-005**: System shall manage procurement officer assignments

#### 4.3.3 Driver Daily Schedule Management (Jadual Harian Pemandu)
**Purpose**: Manage driver schedules and assignments.

**Functional Requirements**:
- **FR-DS-001**: System shall create and manage driver daily schedules
- **FR-DS-002**: System shall assign drivers to vehicles and routes
- **FR-DS-003**: System shall track driver availability and working hours
- **FR-DS-004**: System shall handle driver schedule conflicts and resolutions
- **FR-DS-005**: System shall generate driver schedule reports
- **FR-DS-006**: System shall support schedule modifications and updates

---

## 5. Non-Functional Requirements

### 5.1 Performance Requirements
- **NFR-P-001**: System response time shall not exceed 3 seconds for standard operations
- **NFR-P-002**: System shall support up to 100 concurrent users
- **NFR-P-003**: Database queries shall execute within 2 seconds
- **NFR-P-004**: System shall have 99.5% uptime availability

### 5.2 Usability Requirements
- **NFR-U-001**: System shall provide intuitive user interface
- **NFR-U-002**: System shall support both English and Malay languages
- **NFR-U-003**: System shall be accessible via web browsers
- **NFR-U-004**: System shall provide comprehensive help documentation

### 5.3 Scalability Requirements
- **NFR-S-001**: System shall accommodate organizational growth
- **NFR-S-002**: Database shall support increasing data volumes
- **NFR-S-003**: System architecture shall support horizontal scaling

---

## 6. Business Rules

### 6.1 Vehicle Booking Rules
- **BR-VB-001**: Vehicles cannot be double-booked for the same time period
- **BR-VB-002**: Booking requests must be submitted at least 24 hours in advance (except emergencies)
- **BR-VB-003**: KRJ vehicles have priority for official position holders
- **BR-VB-004**: Maintenance schedules override booking requests

### 6.2 User Access Rules
- **BR-UA-001**: Users can only access data relevant to their role and department
- **BR-UA-002**: Administrative functions require elevated permissions
- **BR-UA-003**: User sessions expire after 30 minutes of inactivity

### 6.3 Maintenance Rules
- **BR-VM-001**: Vehicles due for maintenance cannot be booked
- **BR-VM-002**: Emergency maintenance takes priority over scheduled bookings
- **BR-VM-003**: Maintenance records must be updated within 24 hours of service completion

---

## 7. Data Requirements

### 7.1 Core Entities
- **Vehicles**: Registration, specifications, status, location
- **Users**: Profile, role, permissions, contact information
- **Bookings**: Request details, approval status, schedule
- **Drivers**: License, schedule, assignments, contact information
- **Maintenance**: Service records, costs, vendors, schedules
- **Fuel Cards**: Card details, limits, usage history

### 7.2 Data Relationships
- Users can create multiple bookings
- Vehicles can have multiple maintenance records
- Drivers can be assigned to multiple vehicles
- Fuel cards are assigned to specific vehicles

### 7.3 Data Retention
- Booking records: 3 years
- Maintenance records: Vehicle lifetime
- User activity logs: 1 year
- Fuel usage data: 5 years

---

## 8. Integration Requirements

### 8.1 External Systems
- **INT-001**: Integration with fuel vendor systems (optional)
- **INT-002**: Integration with maintenance service providers
- **INT-003**: Integration with organizational directory services
- **INT-004**: Integration with financial systems for cost tracking

### 8.2 Data Exchange
- **INT-005**: Support for data import/export in standard formats
- **INT-006**: API endpoints for third-party integrations
- **INT-007**: Real-time data synchronization where required

---

## 9. Security Requirements

### 9.1 Authentication and Authorization
- **SEC-001**: Multi-factor authentication for administrative users
- **SEC-002**: Role-based access control implementation
- **SEC-003**: Secure password policies and encryption
- **SEC-004**: Session management and timeout controls

### 9.2 Data Protection
- **SEC-005**: Encryption of sensitive data at rest and in transit
- **SEC-006**: Regular security audits and vulnerability assessments
- **SEC-007**: Backup and disaster recovery procedures
- **SEC-008**: Compliance with data protection regulations

### 9.3 Audit and Logging
- **SEC-009**: Comprehensive audit trail for all system activities
- **SEC-010**: Security event logging and monitoring
- **SEC-011**: Regular log review and analysis procedures

---

## 10. Implementation Phases

Based on development priorities, the implementation shall proceed in the following phases:

### Phase 1: Core Infrastructure
- User management and authentication
- Basic vehicle registration
- Lookup data management

### Phase 2: Booking System
- Vehicle booking functionality
- Basic approval workflow
- Schedule management

### Phase 3: Advanced Features
- Maintenance management
- Fuel card system
- KRJ management

### Phase 4: Reporting and Analytics
- Comprehensive reporting
- Dashboard and analytics
- Performance monitoring

---

## 11. Assumptions and Constraints

### 11.1 Assumptions
- **ASS-001**: Users have basic computer literacy
- **ASS-002**: Reliable internet connectivity is available
- **ASS-003**: Organizational structure data is available
- **ASS-004**: Vehicle information is accurate and up-to-date

### 11.2 Constraints
- **CON-001**: System must comply with government IT policies
- **CON-002**: Budget limitations may affect feature implementation
- **CON-003**: Integration with legacy systems may be limited
- **CON-004**: Data migration from existing systems required

---

## 12. Acceptance Criteria

### 12.1 Functional Acceptance
- All specified functional requirements are implemented and tested
- User acceptance testing completed successfully
- Integration testing with external systems completed
- Performance benchmarks met

### 12.2 Non-Functional Acceptance
- Security requirements validated through penetration testing
- Performance requirements verified under load testing
- Usability requirements confirmed through user testing
- Documentation completed and approved

### 12.3 Go-Live Criteria
- System deployed in production environment
- User training completed
- Data migration successful
- Support procedures established
- Backup and recovery tested

---

## Appendices

### Appendix A: Glossary
- **KRJ**: Kenderaan Kereta Rasmi Jawatan (Official Position Vehicle)
- **JUSA**: Jusa Grade (Senior Government Position Grade)
- **Kad Minyak**: Fuel Card

### Appendix B: Reference Documents
- Organizational policies and procedures
- Government IT standards and guidelines
- Security and compliance requirements

### Appendix C: Contact Information
- Project stakeholders
- Technical team contacts
- Support and maintenance contacts

---

**Document End**

*This Business Requirements Specification serves as the foundation for the Fleet Management System development and implementation.*
