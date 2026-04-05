# API Documentation

## Overview
This directory contains all API endpoints for the Muteesa II School Management System.

## Security Features
- Password hashing using bcrypt (cost factor: 12)
- Rate limiting to prevent brute force attacks
- Input sanitization and validation
- SQL injection prevention using prepared statements
- Session management with regeneration
- Role-based access control

## Endpoints

### Authentication

#### POST /api/register.php
Register a new user account.

**Request Body:**
```json
{
  "username": "string (3-50 chars)",
  "email": "string (valid email)",
  "password": "string (min 8 chars, 1 uppercase, 1 lowercase, 1 number)",
  "confirm_password": "string",
  "user_role": "student|parent|teacher"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Registration successful",
  "data": {
    "user_id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "role": "parent"
  }
}
```

#### POST /api/login.php
Authenticate user and create session.

**Request Body:**
```json
{
  "username": "string (username or email)",
  "password": "string"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user_id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "role": "parent"
  }
}
```

#### POST /api/logout.php
End user session.

**Response:**
```json
{
  "success": true,
  "message": "Logout successful"
}
```

#### POST /api/change_password.php
Change user password (requires authentication).

**Request Body:**
```json
{
  "current_password": "string",
  "new_password": "string",
  "confirm_password": "string"
}
```

#### GET /api/get_user_profile.php
Get authenticated user profile.

**Response:**
```json
{
  "success": true,
  "message": "Profile retrieved successfully",
  "data": {
    "user_id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "user_role": "parent",
    "status": "active",
    "created_at": "2024-01-01 10:00:00",
    "last_login": "2024-01-15 14:30:00"
  }
}
```

### Forms

#### POST /api/submit_admission.php
Submit admission application.

**Request Body:**
```json
{
  "student_name": "string",
  "date_of_birth": "YYYY-MM-DD",
  "class_applying_for": "string",
  "parent_name": "string",
  "phone": "string",
  "email": "string"
}
```

#### POST /api/submit_contact.php
Submit contact form message.

**Request Body:**
```json
{
  "name": "string",
  "email": "string",
  "subject": "string",
  "message": "string (10-5000 chars)"
}
```

## User Roles
- **student**: Student account
- **parent**: Parent/guardian account
- **teacher**: Teacher account
- **admin**: Administrator with full access

## Rate Limiting
- Login: 5 attempts per 5 minutes
- Registration: 3 attempts per hour
- Admission: 3 attempts per hour
- Contact: 5 attempts per hour

## Error Codes
- 200: Success
- 201: Created
- 400: Bad Request (validation error)
- 401: Unauthorized (authentication required)
- 403: Forbidden (insufficient permissions)
- 404: Not Found
- 409: Conflict (duplicate entry)
- 429: Too Many Requests (rate limit exceeded)
- 500: Internal Server Error

## Password Requirements
- Minimum 8 characters
- At least 1 uppercase letter
- At least 1 lowercase letter
- At least 1 number
