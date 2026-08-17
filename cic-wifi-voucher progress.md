# CIC WiFi Voucher System — Development Progress & TODO

## 1. Project Overview

The project is a **stationary CIC WiFi Voucher System** for City of Ilagan College.

The intended setup is:

```text
Students / Visitors
        │
        ▼
CIC WiFi Kiosk
(Raspberry Pi / PC)
        │
        ▼
Laravel CIC WiFi System
        │
        ├──────────────► CIC Student Information System
        │
        └──────────────► TP-Link Omada OC300
                              │
                              ▼
                         CIC WiFi APs
```

The Laravel system will act as the management/application layer.

The **Omada OC300 remains responsible for the actual WiFi network and vouchers**.

The CIC Student Information System remains the authoritative source for student information/authentication once its integration is ready.

---

# 2. Current Environment

## Development Environment

```text
Operating System:
Windows

Development Stack:
Laravel 12.66.0
PHP
Composer
Node.js / npm
Git
MySQL / XAMPP
Livewire
```

Project location:

```text
C:\xampp\htdocs\Wifi Voucher CIC\cic-wifi-voucher
```

Laravel development server is working with:

```text
composer run dev
```

The Laravel default page has been tested successfully.

---

# 3. Database

MySQL database has been created:

```text
Database:
cic_wifi
```

Laravel is successfully connected to MySQL.

Initial Laravel migrations have been executed.

---

# 4. Laravel Authentication

Authentication was initially incomplete because the Laravel installer generated Livewire components but Fortify was not installed.

This was fixed by installing:

```text
laravel/fortify
```

Fortify authentication is now working.

Login route exists:

```text
GET  /login
POST /login
```

Staff authentication has been tested successfully.

Development staff account:

```text
Name:
Maria Santos

Email:
maria.staff@cic.local

Role:
staff

Password:
password
```

Development admin account:

```text
Name:
CIC Administrator

Email:
admin@cic.local

Role:
admin

Password:
password
```

Another development staff:

```text
Name:
Pedro Reyes

Email:
pedro.staff@cic.local

Role:
staff

Password:
password
```

These passwords are development-only and must be changed before production.

---

# 5. User Roles

Current role structure:

```text
admin
staff
```

Intended permissions:

## Admin

Can:

* Manage staff.
* Manage students where appropriate.
* Manage visitors.
* View vouchers.
* Revoke vouchers.
* View logs.
* Configure system.
* Configure Omada integration.
* View reports.

## Staff

Can:

* Log in.
* Authorize visitors.
* Create visitor WiFi vouchers.
* View their visitor voucher activity.

## Student

Students currently do not have a full application account.

They will use the kiosk to authenticate themselves.

---

# 6. Database Models Created

The following models exist:

```text
app/Models/
├── Student.php
├── Visitor.php
├── WifiVoucher.php
├── WifiAccessLog.php
└── User.php
```

---

# 7. Database Tables

## `students`

Current fields:

```text
id
student_id
first_name
last_name
course
year_level
status
created_at
updated_at
```

Example development records:

```text
2026-0001
Juan Dela Cruz
BS Information Technology
1st Year
Active

2026-0002
Maria Santos
BS Computer Science
2nd Year
Active

2026-0003
Pedro Cruz
BS Information Technology
3rd Year
Inactive
```

---

## `visitors`

Current fields:

```text
id
name
purpose
visiting_department
contact_number
authorized_by
status
created_at
updated_at
```

The important field:

```text
authorized_by
```

stores which staff member authorized the visitor.

---

## `wifi_vouchers`

Current fields:

```text
id
student_id
visitor_id
omada_voucher_id
voucher_code
issued_by
voucher_type
duration_minutes
status
issued_at
expires_at
created_at
updated_at
```

Voucher type:

```text
student
visitor
```

A voucher belongs to either:

```text
Student
OR
Visitor
```

---

## `wifi_access_logs`

Current fields:

```text
id
student_id
visitor_id
voucher_id
performed_by
action
ip_address
device_mac
description
created_at
updated_at
```

Intended actions include:

```text
voucher_generated
voucher_viewed
voucher_revoked
visitor_voucher_generated
visitor_authorized
voucher_expired
```

---

# 8. Eloquent Relationships

Relationships have been configured.

## Student

```text
Student
  └── hasMany WifiVoucher
```

Can use:

```php
$student->wifiVouchers
```

Student also has:

```php
$student->full_name
```

---

## Visitor

```text
Visitor
  ├── belongsTo User through authorized_by
  └── hasMany WifiVoucher
```

---

## WifiVoucher

```text
WifiVoucher
  ├── belongsTo Student
  ├── belongsTo Visitor
  └── belongsTo User through issued_by
```

---

## WifiAccessLog

```text
WifiAccessLog
  ├── belongsTo Student
  ├── belongsTo Visitor
  ├── belongsTo WifiVoucher
  └── belongsTo User
```

---

# 9. Current Student Kiosk

Student kiosk route:

```text
/kiosk/student
```

Current flow:

```text
Student
   ↓
Enter Student ID
   ↓
Laravel searches students table
   ↓
Verify student exists
   ↓
Verify status is active
   ↓
Display student information
```

Example:

```text
Student ID:
2026-0001

↓

Juan Dela Cruz
BS Information Technology
1st Year
ACTIVE
```

---

# 10. Current Student Voucher Flow

The student can currently click:

```text
Get WiFi Voucher
```

The system:

```text
Checks for existing active voucher
        │
        ├── Yes → Display existing voucher
        │
        └── No → Generate new voucher
```

The voucher is currently generated through:

```text
MockOmadaService
```

Example:

```text
23ZQ-POS0-YXOB
```

Voucher duration currently:

```text
8 hours
```

Voucher is saved into:

```text
wifi_vouchers
```

and an audit record is saved into:

```text
wifi_access_logs
```

The voucher page successfully displays:

```text
WiFi Voucher
Status
Duration
Expiration
Student Name
```

---

# 11. Important Student Security Problem Identified

Current student authentication is **not secure enough**.

Current process:

```text
Enter Student ID
       ↓
Voucher
```

This means anyone who knows another student's Student ID could potentially claim that student's voucher.

### Decision

Add a **4-digit student PIN**.

Proposed flow:

```text
Student ID
   +
4-digit PIN
   ↓
Verify
   ↓
Active student?
   ↓
Existing voucher?
   ↓
Generate/display voucher
```

The PIN should:

* Be hashed.
* Never be stored as plain text.
* Have limited attempts.
* Lock temporarily after repeated failed attempts.
* Not be reset from the public kiosk.

Long-term, the PIN/authentication should preferably be handled by the CIC Student Information System rather than creating a separate independent credential database.

---

# 12. Mock Omada Integration

Created:

```text
app/Services/MockOmadaService.php
```

Created contract:

```text
app/Contracts/OmadaServiceInterface.php
```

Current interface:

```php
createVoucher(int $durationMinutes = 480): array;

getVoucher(string $voucherId): ?array;
```

Laravel's service container is configured so:

```text
OmadaServiceInterface
        ↓
MockOmadaService
```

This allows us to later replace the mock service without rewriting the voucher controllers.

---

# 13. Real Omada Environment

The school's real Omada environment is available.

```text
Controller:
TP-Link OC300

Omada Controller Version:
6.2.14.12

Controller:
https://192.168.10.4:8043

Hotspot:
Voucher
```

Open API application was created:

```text
App Name:
sample

Role:
Admin

Site Privileges:
Sites
```

Omada ID:

```text
b636cd9bf4959b0660176c20cb823d83
```

Client ID:

```text
76d67d2d9fd9466aa3101a68dd802fba
```

The **Client Secret must not be committed to Git or placed directly in PHP files**.

It should eventually be stored in:

```text
.env
```

Example:

```env
OMADA_URL=https://192.168.10.4:8043
OMADA_ID=
OMADA_CLIENT_ID=
OMADA_CLIENT_SECRET=
```

---

# 14. Omada Integration Status

The actual OC300 API integration has **not yet been completed**.

A Postman authentication test was started, but the OC300 server was temporarily unavailable.

A previous Postman error:

```text
Invalid HTTP Token: Header nameapplication/json
```

was identified as an incorrectly configured `Content-Type` header.

Correct header:

```text
Content-Type: application/json
```

The next real Omada step is:

```text
Laravel / Postman
        ↓
OC300 OAuth
        ↓
Access Token
        ↓
Get Omada/site information
        ↓
Read voucher groups
        ↓
Read existing vouchers
        ↓
Create vouchers
        ↓
Revoke/update vouchers
```

---

# 15. Staff Authentication

Staff middleware was created:

```text
app/Http/Middleware/StaffMiddleware.php
```

Staff middleware allows:

```text
staff
admin
```

The middleware is registered in:

```text
bootstrap/app.php
```

Alias:

```text
staff
```

Staff routes are protected using:

```php
Route::middleware('staff')
```

---

# 16. Staff Dashboard

Current route:

```text
/staff/dashboard
```

Current functionality:

```text
Staff Dashboard
    ↓
Create Visitor WiFi Access
```

The dashboard displays the logged-in staff member.

---

# 17. Visitor Flow

Visitor authorization has already been implemented using the mock Omada service.

Current intended flow:

```text
Staff Login
     ↓
Staff Dashboard
     ↓
Create Visitor WiFi Access
     ↓
Enter visitor details
     ↓
Choose duration
     ↓
Staff authorizes visitor
     ↓
Create visitor record
     ↓
Generate mock Omada voucher
     ↓
Save voucher
     ↓
Save audit log
     ↓
Display visitor voucher
```

Visitor information:

```text
Name
Purpose
Visiting Department
Contact Number
Duration
```

The system records:

```text
authorized_by
issued_by
```

so CIC can identify the staff member responsible for the voucher.

---

# 18. Visitor Security Decision

Visitors should **not** be able to generate their own voucher.

We identified the following loophole:

```text
Student
 ↓
Select Visitor
 ↓
Enter fake name
 ↓
Generate unlimited WiFi vouchers
```

The chosen solution is:

```text
Visitor
   ↓
Requests WiFi from CIC staff
   ↓
Verified staff logs in
   ↓
Staff authorizes visitor
   ↓
Voucher generated
```

This prevents students from abusing the visitor system.

---

# 19. Raspberry Pi Kiosk Plan

The system is intended to be stationary.

Recommended hardware:

```text
Raspberry Pi 5
8 GB RAM
Ethernet
SSD
Monitor/touchscreen
Official power supply
Cooling
```

Preferred architecture:

```text
School Network
      │
      ├── Laravel Server
      ├── CIC Student System
      └── OC300
              │
              ▼
       WiFi Access Points

Laravel Server
      │
      ▼
Raspberry Pi Kiosk
```

The Raspberry Pi should preferably act as the **kiosk/terminal**, not the central backend server.

The kiosk should:

* Auto-start.
* Launch Chromium.
* Open the CIC WiFi application.
* Use full-screen kiosk mode.
* Prevent access to normal desktop functions.
* Automatically reset after inactivity.

---

# 20. Current Application Flow

### Student

```text
CIC Kiosk
    ↓
Student ID
    ↓
Student verification
    ↓
Check active voucher
    ↓
Mock Omada
    ↓
Voucher
    ↓
Display
```

### Visitor

```text
Staff Login
    ↓
Visitor details
    ↓
Staff authorization
    ↓
Mock Omada
    ↓
Voucher
    ↓
Audit log
```

---

# 21. Immediate Priority TODO

These should be completed before expanding the system.

## A. Student PIN authentication

* [ ] Add student PIN field/design.
* [ ] Hash student PIN.
* [ ] Add temporary mock PINs to seed data.
* [ ] Add Student ID + PIN form.
* [ ] Verify PIN using Laravel Hash.
* [ ] Limit failed attempts.
* [ ] Implement temporary lockout.
* [ ] Clear/reset kiosk session after successful voucher display.
* [ ] Prevent student information from remaining on screen.
* [ ] Later replace local PIN verification with CIC Student System authentication.

---

## B. Real Omada integration

* [ ] Confirm OC300 availability.
* [ ] Configure Open API application.
* [ ] Securely add Client Secret to `.env`.
* [ ] Implement `OmadaService`.
* [ ] Implement OAuth token acquisition.
* [ ] Test API authentication.
* [ ] Retrieve Omada site information.
* [ ] Retrieve voucher groups.
* [ ] Retrieve existing voucher data.
* [ ] Identify exact voucher creation API for OC300 6.2.14.12.
* [ ] Implement voucher creation.
* [ ] Implement voucher status retrieval.
* [ ] Implement voucher revocation.
* [ ] Replace `MockOmadaService`.
* [ ] Test real voucher generation.
* [ ] Verify that generated vouchers actually authenticate through Omada captive portal.

---

## C. Voucher reliability

* [ ] Use database transactions for voucher generation.
* [ ] If Omada fails, do not leave incomplete voucher records.
* [ ] Handle duplicate voucher requests safely.
* [ ] Handle expired vouchers.
* [ ] Handle revoked vouchers.
* [ ] Periodically synchronize voucher status with Omada.
* [ ] Prevent duplicate active vouchers.

---

## D. Visitor module improvements

* [ ] Visitor voucher history.
* [ ] Staff-issued voucher history.
* [ ] Visitor voucher revocation.
* [ ] Visitor voucher expiration handling.
* [ ] Visitor search.
* [ ] Admin visitor management.
* [ ] Admin ability to revoke vouchers.

---

## E. Admin Dashboard

Create:

```text
/admin/dashboard
```

Display:

```text
Total Students
Active Student Vouchers
Expired Vouchers
Visitor Vouchers
Visitors Today
Active Staff
Omada Connection Status
```

---

## F. Voucher Management

Admin should be able to:

* [ ] View all vouchers.
* [ ] Filter student/visitor vouchers.
* [ ] Search voucher code.
* [ ] Search student.
* [ ] Search visitor.
* [ ] View expiration.
* [ ] Revoke voucher.
* [ ] View voucher history.
* [ ] View Omada status.

---

## G. Audit Logging

Complete the audit system.

Record:

```text
Who
What
When
Which voucher
Which student/visitor
IP address
Device information where appropriate
```

Actions:

```text
student_verified
student_verification_failed
voucher_generated
voucher_viewed
voucher_revoked
visitor_authorized
visitor_voucher_generated
staff_login
staff_logout
```

---

# 22. CIC Student System Integration

The CIC Student Information System is still under development but is available for future integration.

Determine:

```text
API available?
Database?
Authentication API?
Student lookup endpoint?
Student status endpoint?
```

Preferred architecture:

```text
CIC Student System
       ↓
Student API
       ↓
Laravel StudentService
       ↓
CIC WiFi System
```

Do not tightly couple the WiFi application directly to the student database unless CIC's IT architecture explicitly permits this.

Create an abstraction similar to:

```text
StudentServiceInterface
```

Possible implementations:

```text
MockStudentService
CICStudentService
```

---

# 23. Student Authentication Final Design

Preferred final student flow:

```text
┌─────────────────────────────┐
│       CIC CAMPUS WIFI       │
│                             │
│       STUDENT ACCESS        │
│                             │
│ Student ID                  │
│ [ 2026-0001 ]               │
│                             │
│ 4-Digit PIN                 │
│ [ • • • • ]                 │
│                             │
│ [ VERIFY & GET WIFI ]       │
│                             │
│ Forgot PIN? Contact MIS     │
└─────────────────────────────┘
```

Flow:

```text
Student ID + PIN
       ↓
Student System
       ↓
Identity verification
       ↓
Student active?
       ↓
Existing active voucher?
       ├── YES → Show existing voucher
       └── NO  → Create voucher
```

---

# 24. QR Code

Eventually add QR code support.

Voucher screen:

```text
CIC CAMPUS WIFI

Voucher:
XXXX-XXXX-XXXX

[ QR CODE ]

Status:
ACTIVE

Expires:
DATE/TIME
```

The QR implementation should be finalized only after confirming exactly how Omada expects voucher credentials to be used.

---

# 25. Printing

Optional kiosk functionality:

```text
[ PRINT VOUCHER ]
```

Potential use:

* Student receives printed voucher.
* Visitor receives printed voucher.
* Staff can print visitor voucher.

---

# 26. Kiosk UX

Kiosk main screen:

```text
CITY OF ILAGAN COLLEGE

CIC CAMPUS WIFI

[ STUDENT ]

[ VISITOR / STAFF ]
```

Recommended design:

### Student

Self-service.

### Visitor

Staff-assisted.

### Staff

Separate authenticated staff portal.

---

# 27. Security Requirements

Implement before production:

* [ ] HTTPS.
* [ ] Secure session handling.
* [ ] CSRF protection.
* [ ] Rate limiting.
* [ ] PIN hashing.
* [ ] PIN attempt limits.
* [ ] Temporary lockout.
* [ ] Role-based authorization.
* [ ] Secure `.env` credentials.
* [ ] Never expose Omada Client Secret.
* [ ] Never expose student credentials.
* [ ] Audit logging.
* [ ] Session timeout.
* [ ] Kiosk auto-reset.
* [ ] Database backups.
* [ ] Restrict admin access.
* [ ] Restrict Omada API access to the school network.

---

# 28. Testing Requirements

## Student

* [ ] Valid Student ID + correct PIN.
* [ ] Invalid Student ID.
* [ ] Incorrect PIN.
* [ ] Three failed PIN attempts.
* [ ] Locked account.
* [ ] Inactive student.
* [ ] Existing active voucher.
* [ ] Expired voucher.
* [ ] Voucher generation failure.
* [ ] Omada unavailable.
* [ ] Student leaves kiosk without logging out.
* [ ] Kiosk automatically resets.

## Visitor

* [ ] Staff authorization.
* [ ] Invalid staff.
* [ ] Invalid visitor information.
* [ ] Visitor voucher generation.
* [ ] Voucher expiration.
* [ ] Voucher revocation.
* [ ] Staff audit trail.

## Omada

* [ ] API authentication.
* [ ] Invalid credentials.
* [ ] Controller unreachable.
* [ ] Site lookup.
* [ ] Voucher retrieval.
* [ ] Voucher generation.
* [ ] Voucher expiration.
* [ ] Voucher revocation.

---

# 29. Current Known Issues

### Issue 1 — Student ID-only authentication

**Status:** Not solved yet.

Problem:

```text
Anyone who knows a student's ID can potentially claim the voucher.
```

Solution chosen:

```text
Student ID + 4-digit PIN
```

---

### Issue 2 — Real Omada API not connected

**Status:** Pending.

Current:

```text
MockOmadaService
```

Target:

```text
OmadaService
        ↓
OC300 6.2.14.12
```

---

### Issue 3 — Real CIC Student System not integrated

**Status:** Pending.

Current:

```text
Local students table
```

Target:

```text
CIC Student Information System
```

---

### Issue 4 — Development passwords

**Status:** Temporary.

Current:

```text
password
```

These must be replaced before production.

---

# 30. Recommended Development Order From Here

```text
1. Student PIN authentication
        ↓
2. Student failed-attempt/lockout protection
        ↓
3. Student kiosk session reset
        ↓
4. Improve visitor module
        ↓
5. Build admin dashboard
        ↓
6. Build voucher management
        ↓
7. Complete audit logs
        ↓
8. Implement real OmadaService
        ↓
9. Test actual OC300 voucher retrieval
        ↓
10. Test actual OC300 voucher creation
        ↓
11. Verify real WiFi authentication
        ↓
12. Build StudentService abstraction
        ↓
13. Integrate CIC Student System
        ↓
14. Add QR code
        ↓
15. Add printing
        ↓
16. Build Raspberry Pi kiosk deployment
        ↓
17. Security hardening
        ↓
18. Full testing
        ↓
19. Deployment
```

---

# 31. Target Final Architecture

```text
                         CIC NETWORK
                              │
             ┌────────────────┼────────────────┐
             │                │                │
             ▼                ▼                ▼
      CIC Student       Laravel CIC WiFi     Omada OC300
       System              Server           192.168.10.4
             │                │                │
             │                │                ▼
             │                │             WiFi APs
             │                │
             │          ┌─────┴─────┐
             │          │           │
             │          ▼           ▼
             │       Kiosk 1     Kiosk 2
             │       Pi 5        Pi 5
             │
             ▼
      Student Verification
```

## Final Student Flow

```text
Student
   ↓
CIC WiFi Kiosk
   ↓
Student ID
   ↓
4-Digit PIN
   ↓
CIC Student System
   ↓
Verified
   ↓
Check active voucher
   ↓
Omada
   ↓
Real WiFi Voucher
   ↓
Display / QR / Print
```

## Final Visitor Flow

```text
Visitor
   ↓
CIC Office
   ↓
Verified Staff
   ↓
Visitor information
   ↓
Staff authorization
   ↓
Omada
   ↓
Visitor WiFi Voucher
   ↓
Display / QR / Print
```

---

# 32. MVP Definition

The project should be considered **MVP complete** when the following two workflows work with the real systems:

### Student MVP

```text
Student ID
+
4-digit PIN
        ↓
CIC Student System
        ↓
Verify
        ↓
OC300
        ↓
Real voucher
        ↓
Student receives WiFi access
```

### Visitor MVP

```text
Verified Staff
        ↓
Visitor information
        ↓
Staff authorization
        ↓
OC300
        ↓
Real voucher
        ↓
Visitor receives WiFi access
```

Everything else can be considered **Phase 2 / enhancement work**.

---

# 33. Important Instruction for the AI Agent Continuing Development

The next developer/AI agent should **not rebuild the project from scratch**.

The existing Laravel application already contains:

```text
Student model
Visitor model
WifiVoucher model
WifiAccessLog model
MockOmadaService
OmadaServiceInterface
StaffMiddleware
Student Kiosk
Student Voucher Flow
Staff Login
Staff Dashboard
Visitor Voucher Flow
```

The immediate next task is:

> **Implement secure Student ID + 4-digit PIN authentication with hashed PINs, failed-attempt protection, and kiosk session reset, while preserving the existing student voucher flow.**

After that, proceed to the real Omada API integration.

Do not remove the `MockOmadaService`; keep it available for local development/testing.
