# Money Management App API Documentation

## Overview

This API documentation provides comprehensive endpoints for the Money Management App Flutter mobile application. The API allows users to manage their finances including income tracking, expense management, budget planning, and financial reporting.

**Base URL:** `http://localhost:8000/api`

**Authentication:** Bearer Token (Laravel Sanctum)

## Table of Contents

1. [Authentication](#authentication)
2. [User Profile](#user-profile)
3. [Dashboard](#dashboard)
4. [Categories](#categories)
5. [Income Management](#income-management)
6. [Expense Management](#expense-management)
7. [Budget Management](#budget-management)
8. [Reports](#reports)
9. [Error Handling](#error-handling)
10. [Flutter Integration Example](#flutter-integration-example)

---

## Authentication

### Register User
Create a new user account.

**Endpoint:** `POST /api/register`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "user",
      "created_at": "2025-12-10T10:30:00.000000Z",
      "updated_at": "2025-12-10T10:30:00.000000Z"
    },
    "token": "1|abc123def456...",
    "token_type": "Bearer"
  }
}
```

### Login User
Authenticate user and receive access token.

**Endpoint:** `POST /api/login`

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "user"
    },
    "token": "1|abc123def456...",
    "token_type": "Bearer"
  }
}
```

### Logout User
Revoke the current access token.

**Endpoint:** `POST /api/logout`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Response (200):**
```json
{
  "success": true,
  "message": "Successfully logged out"
}
```

---

## User Profile

### Get Current User
Retrieve authenticated user information.

**Endpoint:** `GET /api/user`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "user",
      "created_at": "2025-12-10T10:30:00.000000Z"
    }
  }
}
```

### Update Profile
Update user profile information.

**Endpoint:** `PUT /api/user/profile`

**Request Body:**
```json
{
  "name": "John Updated",
  "email": "johnupdated@example.com"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Updated",
      "email": "johnupdated@example.com",
      "role": "user"
    }
  }
}
```

### Update Password
Change user password.

**Endpoint:** `PUT /api/user/password`

**Request Body:**
```json
{
  "current_password": "oldpassword123",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Password updated successfully"
}
```

---

## Dashboard

### Get Dashboard Data
Retrieve user's financial dashboard information.

**Endpoint:** `GET /api/dashboard`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "monthly_income": 5000000,
    "monthly_expense": 3000000,
    "total_balance": 2000000,
    "recent_transactions": [
      {
        "id": 1,
        "type": "income",
        "description": "Salary",
        "amount": 5000000,
        "date": "2025-12-01",
        "category": {
          "id": 1,
          "name": "Salary",
          "type": "income"
        }
      }
    ],
    "active_budgets": [
      {
        "id": 1,
        "name": "Food Budget",
        "amount": 1000000,
        "spent": 500000,
        "remaining": 500000,
        "percentage": 50
      }
    ]
  }
}
```

---

## Categories

### Get Categories
Retrieve all transaction categories.

**Endpoint:** `GET /api/categories`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "income_categories": [
      {
        "id": 1,
        "name": "Salary",
        "type": "income",
        "created_at": "2025-12-10T10:30:00.000000Z"
      },
      {
        "id": 2,
        "name": "Freelance",
        "type": "income",
        "created_at": "2025-12-10T10:30:00.000000Z"
      }
    ],
    "expense_categories": [
      {
        "id": 3,
        "name": "Food",
        "type": "expense",
        "created_at": "2025-12-10T10:30:00.000000Z"
      },
      {
        "id": 4,
        "name": "Transportation",
        "type": "expense",
        "created_at": "2025-12-10T10:30:00.000000Z"
      }
    ]
  }
}
```

---

## Income Management

### Get Income List
Retrieve user's income records.

**Endpoint:** `GET /api/income`

**Query Parameters:**
- `page` (integer): Page number for pagination
- `per_page` (integer): Number of items per page (default: 10)
- `category_id` (integer): Filter by category ID
- `date_from` (date): Filter from date (YYYY-MM-DD)
- `date_to` (date): Filter to date (YYYY-MM-DD)

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "income": [
      {
        "id": 1,
        "description": "Monthly Salary",
        "amount": 5000000,
        "date": "2025-12-01",
        "notes": "December salary",
        "created_at": "2025-12-01T08:00:00.000000Z",
        "category": {
          "id": 1,
          "name": "Salary",
          "type": "income"
        }
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 10,
      "total": 1,
      "from": 1,
      "to": 1
    },
    "summary": {
      "total_income": 5000000,
      "monthly_income": 5000000
    }
  }
}
```

### Create Income
Add a new income record.

**Endpoint:** `POST /api/income`

**Request Body:**
```json
{
  "category_id": 1,
  "description": "Freelance Project",
  "amount": 2500000,
  "date": "2025-12-15",
  "notes": "Web development project"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Income created successfully",
  "data": {
    "income": {
      "id": 2,
      "category_id": 1,
      "description": "Freelance Project",
      "amount": 2500000,
      "date": "2025-12-15",
      "notes": "Web development project",
      "user_id": 1,
      "created_at": "2025-12-10T10:45:00.000000Z",
      "updated_at": "2025-12-10T10:45:00.000000Z",
      "category": {
        "id": 1,
        "name": "Salary",
        "type": "income"
      }
    }
  }
}
```

### Get Income Details
Retrieve specific income record.

**Endpoint:** `GET /api/income/{id}`

**Response (200):**
```json
{
  "success": true,
  "data": {
    "income": {
      "id": 1,
      "description": "Monthly Salary",
      "amount": 5000000,
      "date": "2025-12-01",
      "notes": "December salary",
      "created_at": "2025-12-01T08:00:00.000000Z",
      "updated_at": "2025-12-01T08:00:00.000000Z",
      "category": {
        "id": 1,
        "name": "Salary",
        "type": "income"
      }
    }
  }
}
```

### Update Income
Update existing income record.

**Endpoint:** `PUT /api/income/{id}`

**Request Body:**
```json
{
  "category_id": 1,
  "description": "Updated Salary",
  "amount": 5500000,
  "date": "2025-12-01",
  "notes": "Updated December salary with bonus"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Income updated successfully",
  "data": {
    "income": {
      "id": 1,
      "description": "Updated Salary",
      "amount": 5500000,
      "date": "2025-12-01",
      "notes": "Updated December salary with bonus",
      "updated_at": "2025-12-10T11:00:00.000000Z"
    }
  }
}
```

### Delete Income
Delete income record.

**Endpoint:** `DELETE /api/income/{id}`

**Response (200):**
```json
{
  "success": true,
  "message": "Income deleted successfully"
}
```

---

## Expense Management

### Get Expenses List
Retrieve user's expense records.

**Endpoint:** `GET /api/expenses`

**Query Parameters:**
- `page` (integer): Page number for pagination
- `per_page` (integer): Number of items per page (default: 10)
- `category_id` (integer): Filter by category ID
- `date_from` (date): Filter from date (YYYY-MM-DD)
- `date_to` (date): Filter to date (YYYY-MM-DD)

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "expenses": [
      {
        "id": 1,
        "description": "Lunch at Restaurant",
        "amount": 150000,
        "date": "2025-12-10",
        "notes": "Business meeting",
        "created_at": "2025-12-10T12:00:00.000000Z",
        "category": {
          "id": 3,
          "name": "Food",
          "type": "expense"
        }
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 10,
      "total": 1,
      "from": 1,
      "to": 1
    },
    "summary": {
      "total_expenses": 150000,
      "monthly_expenses": 150000
    }
  }
}
```

### Create Expense
Add a new expense record.

**Endpoint:** `POST /api/expenses`

**Request Body:**
```json
{
  "category_id": 4,
  "description": "Gasoline",
  "amount": 100000,
  "date": "2025-12-10",
  "notes": "Weekly fuel"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Expense created successfully",
  "data": {
    "expense": {
      "id": 2,
      "category_id": 4,
      "description": "Gasoline",
      "amount": 100000,
      "date": "2025-12-10",
      "notes": "Weekly fuel",
      "user_id": 1,
      "created_at": "2025-12-10T12:30:00.000000Z",
      "updated_at": "2025-12-10T12:30:00.000000Z",
      "category": {
        "id": 4,
        "name": "Transportation",
        "type": "expense"
      }
    }
  }
}
```

### Get Expense Details
Retrieve specific expense record.

**Endpoint:** `GET /api/expenses/{id}`

### Update Expense
Update existing expense record.

**Endpoint:** `PUT /api/expenses/{id}`

**Request Body:**
```json
{
  "category_id": 4,
  "description": "Updated Gasoline",
  "amount": 120000,
  "date": "2025-12-10",
  "notes": "Updated weekly fuel cost"
}
```

### Delete Expense
Delete expense record.

**Endpoint:** `DELETE /api/expenses/{id}`

---

## Budget Management

### Get Budgets List
Retrieve user's budget records.

**Endpoint:** `GET /api/budgets`

**Query Parameters:**
- `page` (integer): Page number for pagination
- `per_page` (integer): Number of items per page (default: 10)
- `month_year` (string): Filter by month-year (YYYY-MM)
- `is_active` (boolean): Filter by active status

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "budgets": [
      {
        "id": 1,
        "name": "Monthly Food Budget",
        "amount": 2000000,
        "period": "monthly",
        "month_year": "2025-12",
        "is_active": true,
        "created_at": "2025-12-01T00:00:00.000000Z",
        "category": {
          "id": 3,
          "name": "Food",
          "type": "expense"
        },
        "spent": 1500000,
        "remaining": 500000,
        "percentage": 75,
        "status": "warning"
      }
    ],
    "summary": {
      "total_budget": 2000000,
      "total_spent": 1500000,
      "total_remaining": 500000,
      "active_budgets": 1
    }
  }
}
```

### Create Budget
Add a new budget record.

**Endpoint:** `POST /api/budgets`

**Request Body:**
```json
{
  "category_id": 3,
  "name": "Food Budget 2025",
  "amount": 2500000,
  "period": "monthly",
  "month_year": "2025-12",
  "notes": "Monthly food expenses budget"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Budget created successfully",
  "data": {
    "budget": {
      "id": 2,
      "category_id": 3,
      "name": "Food Budget 2025",
      "amount": 2500000,
      "period": "monthly",
      "month_year": "2025-12",
      "is_active": true,
      "notes": "Monthly food expenses budget",
      "user_id": 1,
      "created_at": "2025-12-10T13:00:00.000000Z",
      "updated_at": "2025-12-10T13:00:00.000000Z",
      "category": {
        "id": 3,
        "name": "Food",
        "type": "expense"
      }
    }
  }
}
```

### Get Budget Details
Retrieve specific budget record.

**Endpoint:** `GET /api/budgets/{id}`

### Update Budget
Update existing budget record.

**Endpoint:** `PUT /api/budgets/{id}`

**Request Body:**
```json
{
  "name": "Updated Food Budget",
  "amount": 3000000,
  "period": "monthly",
  "month_year": "2025-12",
  "is_active": true,
  "notes": "Updated monthly food budget"
}
```

### Toggle Budget Status
Activate/deactivate budget.

**Endpoint:** `PUT /api/budgets/{id}/toggle`

**Response (200):**
```json
{
  "success": true,
  "message": "Budget status updated successfully",
  "data": {
    "budget": {
      "id": 1,
      "is_active": false
    }
  }
}
```

### Delete Budget
Delete budget record.

**Endpoint:** `DELETE /api/budgets/{id}`

---

## Reports

### Get Financial Reports
Retrieve user's financial reports and analytics.

**Endpoint:** `GET /api/reports`

**Query Parameters:**
- `type` (string): Report type ('monthly', 'yearly', 'custom')
- `date_from` (date): Start date for custom range
- `date_to` (date): End date for custom range

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "summary": {
      "total_income": 8000000,
      "total_expenses": 4500000,
      "net_income": 3500000,
      "period": "2025-12"
    },
    "income_by_category": [
      {
        "category": "Salary",
        "amount": 5000000,
        "percentage": 62.5
      },
      {
        "category": "Freelance",
        "amount": 3000000,
        "percentage": 37.5
      }
    ],
    "expenses_by_category": [
      {
        "category": "Food",
        "amount": 2000000,
        "percentage": 44.4
      },
      {
        "category": "Transportation",
        "amount": 1500000,
        "percentage": 33.3
      },
      {
        "category": "Others",
        "amount": 1000000,
        "percentage": 22.2
      }
    ],
    "daily_trends": [
      {
        "date": "2025-12-01",
        "income": 5000000,
        "expenses": 0
      },
      {
        "date": "2025-12-02",
        "income": 0,
        "expenses": 250000
      }
    ]
  }
}
```

---

## Error Handling

### Standard Error Response Format

All API endpoints return errors in the following format:

```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field_name": ["Error message for this field"]
  }
}
```

### Common HTTP Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

### Authentication Errors

**401 Unauthorized:**
```json
{
  "success": false,
  "message": "Unauthenticated."
}
```

**403 Forbidden (Admin attempting API access):**
```json
{
  "success": false,
  "message": "Admin users cannot access the mobile API"
}
```

### Validation Errors

**422 Unprocessable Entity:**
```json
{
  "success": false,
  "message": "Validation errors",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

---

## Flutter Integration Example

### HTTP Client Setup

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  final String baseUrl = 'http://localhost:8000/api';
  String? _token;

  // Set token after login
  void setToken(String token) {
    _token = token;
  }

  // Get headers with authentication
  Map<String, String> _getHeaders() {
    Map<String, String> headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };

    if (_token != null) {
      headers['Authorization'] = 'Bearer $_token';
    }

    return headers;
  }

  // Login example
  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: _getHeaders(),
      body: jsonEncode({
        'email': email,
        'password': password,
      }),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      if (data['success']) {
        setToken(data['data']['token']);
        return data;
      }
    }

    throw Exception('Login failed: ${response.body}');
  }

  // Get income example
  Future<Map<String, dynamic>> getIncome() async {
    final response = await http.get(
      Uri.parse('$baseUrl/income'),
      headers: _getHeaders(),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }

    throw Exception('Failed to load income: ${response.body}');
  }

  // Create income example
  Future<Map<String, dynamic>> createIncome(Map<String, dynamic> incomeData) async {
    final response = await http.post(
      Uri.parse('$baseUrl/income'),
      headers: _getHeaders(),
      body: jsonEncode(incomeData),
    );

    if (response.statusCode == 201) {
      return jsonDecode(response.body);
    }

    throw Exception('Failed to create income: ${response.body}');
  }
}
```

### Usage Example in Flutter

```dart
class IncomeScreen extends StatefulWidget {
  @override
  _IncomeScreenState createState() => _IncomeScreenState();
}

class _IncomeScreenState extends State<IncomeScreen> {
  final ApiService _apiService = ApiService();
  List<dynamic> _incomeList = [];
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    _loadIncome();
  }

  Future<void> _loadIncome() async {
    setState(() => _isLoading = true);

    try {
      final response = await _apiService.getIncome();
      setState(() {
        _incomeList = response['data']['income'];
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: ${e.toString()}')),
      );
    }
  }

  Future<void> _addIncome(Map<String, dynamic> incomeData) async {
    try {
      await _apiService.createIncome(incomeData);
      _loadIncome(); // Refresh the list
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Income added successfully')),
      );
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: ${e.toString()}')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Income')),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : ListView.builder(
              itemCount: _incomeList.length,
              itemBuilder: (context, index) {
                final income = _incomeList[index];
                return ListTile(
                  title: Text(income['description']),
                  subtitle: Text(income['date']),
                  trailing: Text(
                    'Rp ${income['amount']}',
                    style: TextStyle(
                      color: Colors.green,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                );
              },
            ),
      floatingActionButton: FloatingActionButton(
        onPressed: () => _showAddIncomeDialog(),
        child: Icon(Icons.add),
      ),
    );
  }

  void _showAddIncomeDialog() {
    // Show dialog to add new income
    // Call _addIncome with form data
  }
}
```

---

## Testing with Postman

You can use the following Postman collection to test all endpoints:

```json
{
  "info": {
    "name": "Money Management API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "variable": [
    {
      "key": "baseUrl",
      "value": "http://localhost:8000/api"
    },
    {
      "key": "token",
      "value": ""
    }
  ],
  "item": [
    {
      "name": "Authentication",
      "item": [
        {
          "name": "Register",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"name\": \"Test User\",\n  \"email\": \"test@example.com\",\n  \"password\": \"password123\",\n  \"password_confirmation\": \"password123\"\n}"
            },
            "url": {
              "raw": "{{baseUrl}}/register"
            }
          }
        },
        {
          "name": "Login",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"email\": \"test@example.com\",\n  \"password\": \"password123\"\n}"
            },
            "url": {
              "raw": "{{baseUrl}}/login"
            }
          },
          "event": [
            {
              "listen": "test",
              "script": {
                "exec": [
                  "if (pm.response.code === 200) {",
                  "    const response = pm.response.json();",
                  "    pm.collectionVariables.set('token', response.data.token);",
                  "}"
                ]
              }
            }
          ]
        }
      ]
    }
  ]
}
```

---

## Important Notes

### Security
- Always use HTTPS in production
- Store tokens securely on the device
- Implement token refresh mechanism if needed
- Validate all user inputs

### Rate Limiting
- API may implement rate limiting to prevent abuse
- Handle 429 Too Many Requests responses appropriately

### Data Validation
- All monetary values should be handled as integers (in Indonesian Rupiah)
- Date format: YYYY-MM-DD
- Amount precision: Use integer for Rupiah values (no decimals)

### Pagination
- Most list endpoints support pagination
- Default page size: 10 items
- Maximum page size: 50 items

### Error Handling
- Always check the `success` field in responses
- Handle network errors gracefully
- Show user-friendly error messages

This API documentation provides all necessary endpoints for building a complete Flutter mobile application for the Money Management system.