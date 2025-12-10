# Flutter API Setup Guide

## Quick Start for Flutter Developers

This guide helps you quickly integrate the Money Management App API with your Flutter application.

## Prerequisites

1. **Flutter SDK** installed (>= 2.0.0)
2. **HTTP package** for API requests
3. **JSON serialization** for data handling

## Installation

Add these dependencies to your `pubspec.yaml`:

```yaml
dependencies:
  flutter:
    sdk: flutter

  # HTTP client
  http: ^1.1.0

  # Local storage for tokens
  shared_preferences: ^2.2.0

  # State management (optional)
  provider: ^6.0.5

  # JSON serialization
  json_annotation: ^4.8.0

dev_dependencies:
  flutter_test:
    sdk: flutter

  # Code generation for JSON
  json_serializable: ^6.7.0
  build_runner: ^2.4.6
```

Run `flutter pub get` to install dependencies.

## Core API Service

Create a base API service class:

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static const String _baseUrl = 'http://localhost:8000/api';
  static String? _token;

  // Initialize and load stored token
  static Future<void> init() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('auth_token');
  }

  // Save token to local storage
  static Future<void> _saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
    _token = token;
  }

  // Clear token on logout
  static Future<void> _clearToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
    _token = null;
  }

  // Get headers with authentication
  static Map<String, String> _getHeaders({bool requireAuth = false}) {
    Map<String, String> headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };

    if (requireAuth && _token != null) {
      headers['Authorization'] = 'Bearer $_token';
    }

    return headers;
  }

  // Handle API errors
  static Map<String, dynamic> _handleError(http.Response response) {
    final Map<String, dynamic> responseData = jsonDecode(response.body);

    throw Exception(
      responseData['message'] ??
      'Error: ${response.statusCode} - ${response.reasonPhrase}'
    );
  }

  // Authentication methods
  static Future<Map<String, dynamic>> login(
    String email,
    String password
  ) async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl/login'),
        headers: _getHeaders(),
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          await _saveToken(data['data']['token']);
          return data;
        }
      }

      return _handleError(response);
    } catch (e) {
      throw Exception('Login failed: ${e.toString()}');
    }
  }

  static Future<Map<String, dynamic>> register(
    String name,
    String email,
    String password,
    String passwordConfirmation,
  ) async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl/register'),
        headers: _getHeaders(),
        body: jsonEncode({
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': passwordConfirmation,
        }),
      );

      if (response.statusCode == 201) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          await _saveToken(data['data']['token']);
          return data;
        }
      }

      return _handleError(response);
    } catch (e) {
      throw Exception('Registration failed: ${e.toString()}');
    }
  }

  static Future<Map<String, dynamic>> logout() async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl/logout'),
        headers: _getHeaders(requireAuth: true),
      );

      if (response.statusCode == 200) {
        await _clearToken();
        return jsonDecode(response.body);
      }

      return _handleError(response);
    } catch (e) {
      throw Exception('Logout failed: ${e.toString()}');
    }
  }

  // Get current user info
  static Future<Map<String, dynamic>> getCurrentUser() async {
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl/user'),
        headers: _getHeaders(requireAuth: true),
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }

      return _handleError(response);
    } catch (e) {
      throw Exception('Failed to get user info: ${e.toString()}');
    }
  }

  // Income management methods
  static Future<Map<String, dynamic>> getIncome({
    int page = 1,
    int perPage = 10,
    int? categoryId,
    String? dateFrom,
    String? dateTo,
  }) async {
    try {
      final Uri uri = Uri.parse('$_baseUrl/income').replace(queryParameters: {
        'page': page.toString(),
        'per_page': perPage.toString(),
        if (categoryId != null) 'category_id': categoryId.toString(),
        if (dateFrom != null) 'date_from': dateFrom!,
        if (dateTo != null) 'date_to': dateTo!,
      });

      final response = await http.get(
        uri,
        headers: _getHeaders(requireAuth: true),
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }

      return _handleError(response);
    } catch (e) {
      throw Exception('Failed to get income: ${e.toString()}');
    }
  }

  static Future<Map<String, dynamic>> createIncome(Map<String, dynamic> incomeData) async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl/income'),
        headers: _getHeaders(requireAuth: true),
        body: jsonEncode(incomeData),
      );

      if (response.statusCode == 201) {
        return jsonDecode(response.body);
      }

      return _handleError(response);
    } catch (e) {
      throw Exception('Failed to create income: ${e.toString()}');
    }
  }

  // Expense management methods
  static Future<Map<String, dynamic>> getExpenses({
    int page = 1,
    int perPage = 10,
    int? categoryId,
    String? dateFrom,
    String? dateTo,
  }) async {
    try {
      final Uri uri = Uri.parse('$_baseUrl/expenses').replace(queryParameters: {
        'page': page.toString(),
        'per_page': perPage.toString(),
        if (categoryId != null) 'category_id': categoryId.toString(),
        if (dateFrom != null) 'date_from': dateFrom!,
        if (dateTo != null) 'date_to': dateTo!,
      });

      final response = await http.get(
        uri,
        headers: _getHeaders(requireAuth: true),
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }

      return _handleError(response);
    } catch (e) {
      throw Exception('Failed to get expenses: ${e.toString()}');
    }
  }

  static Future<Map<String, dynamic>> createExpense(Map<String, dynamic> expenseData) async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl/expenses'),
        headers: _getHeaders(requireAuth: true),
        body: jsonEncode(expenseData),
      );

      if (response.statusCode == 201) {
        return jsonDecode(response.body);
      }

      return _handleError(response);
    } catch (e) {
      throw Exception('Failed to create expense: ${e.toString()}');
    }
  }

  // Budget management methods
  static Future<Map<String, dynamic>> getBudgets({
    int page = 1,
    int perPage = 10,
    String? monthYear,
    bool? isActive,
  }) async {
    try {
      final Uri uri = Uri.parse('$_baseUrl/budgets').replace(queryParameters: {
        'page': page.toString(),
        'per_page': perPage.toString(),
        if (monthYear != null) 'month_year': monthYear!,
        if (isActive != null) 'is_active': isActive.toString(),
      });

      final response = await http.get(
        uri,
        headers: _getHeaders(requireAuth: true),
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }

      return _handleError(response);
    } catch (e) {
      throw Exception('Failed to get budgets: ${e.toString()}');
    }
  }

  // Categories
  static Future<Map<String, dynamic>> getCategories() async {
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl/categories'),
        headers: _getHeaders(requireAuth: true),
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }

      return _handleError(response);
    } catch (e) {
      throw Exception('Failed to get categories: ${e.toString()}');
    }
  }

  // Dashboard
  static Future<Map<String, dynamic>> getDashboard() async {
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl/dashboard'),
        headers: _getHeaders(requireAuth: true),
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }

      return _handleError(response);
    } catch (e) {
      throw Exception('Failed to get dashboard: ${e.toString()}');
    }
  }

  // Reports
  static Future<Map<String, dynamic>> getReports({
    String type = 'monthly',
    String? dateFrom,
    String? dateTo,
  }) async {
    try {
      final Uri uri = Uri.parse('$_baseUrl/reports').replace(queryParameters: {
        'type': type,
        if (dateFrom != null) 'date_from': dateFrom!,
        if (dateTo != null) 'date_to': dateTo!,
      });

      final response = await http.get(
        uri,
        headers: _getHeaders(requireAuth: true),
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }

      return _handleError(response);
    } catch (e) {
      throw Exception('Failed to get reports: ${e.toString()}');
    }
  }
}
```

## Data Models

Create data models for type safety:

```dart
// models/user.dart
class User {
  final int id;
  final String name;
  final String email;
  final String role;
  final DateTime? createdAt;

  User({
    required this.id,
    required this.name,
    required this.email,
    required this.role,
    this.createdAt,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      role: json['role'],
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
    );
  }
}

// models/income.dart
class Income {
  final int id;
  final String description;
  final int amount;
  final DateTime date;
  final String? notes;
  final Category category;
  final DateTime createdAt;

  Income({
    required this.id,
    required this.description,
    required this.amount,
    required this.date,
    this.notes,
    required this.category,
    required this.createdAt,
  });

  factory Income.fromJson(Map<String, dynamic> json) {
    return Income(
      id: json['id'],
      description: json['description'],
      amount: json['amount'],
      date: DateTime.parse(json['date']),
      notes: json['notes'],
      category: Category.fromJson(json['category']),
      createdAt: DateTime.parse(json['created_at']),
    );
  }
}

// models/category.dart
class Category {
  final int id;
  final String name;
  final String type;

  Category({
    required this.id,
    required this.name,
    required this.type,
  });

  factory Category.fromJson(Map<String, dynamic> json) {
    return Category(
      id: json['id'],
      name: json['name'],
      type: json['type'],
    );
  }
}

// models/expense.dart
class Expense {
  final int id;
  final String description;
  final int amount;
  final DateTime date;
  final String? notes;
  final Category category;
  final DateTime createdAt;

  Expense({
    required this.id,
    required this.description,
    required this.amount,
    required this.date,
    this.notes,
    required this.category,
    required this.createdAt,
  });

  factory Expense.fromJson(Map<String, dynamic> json) {
    return Expense(
      id: json['id'],
      description: json['description'],
      amount: json['amount'],
      date: DateTime.parse(json['date']),
      notes: json['notes'],
      category: Category.fromJson(json['category']),
      createdAt: DateTime.parse(json['created_at']),
    );
  }
}
```

## Authentication Provider

Create a simple authentication provider:

```dart
import 'package:flutter/foundation.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user.dart';

class AuthProvider extends ChangeNotifier {
  User? _user;
  bool _isLoading = false;
  String? _errorMessage;

  User? get user => _user;
  bool get isLoading => _isLoading;
  bool get isAuthenticated => _user != null;
  String? get errorMessage => _errorMessage;

  Future<void> login(String email, String password) async {
    _setLoading(true);
    _errorMessage = null;

    try {
      final response = await ApiService.login(email, password);
      if (response['success']) {
        _user = User.fromJson(response['data']['user']);
        await _saveUserToPrefs();
      }
    } catch (e) {
      _errorMessage = e.toString();
    } finally {
      _setLoading(false);
    }
  }

  Future<void> register(
    String name,
    String email,
    String password,
    String passwordConfirmation,
  ) async {
    _setLoading(true);
    _errorMessage = null;

    try {
      final response = await ApiService.register(
        name,
        email,
        password,
        passwordConfirmation,
      );
      if (response['success']) {
        _user = User.fromJson(response['data']['user']);
        await _saveUserToPrefs();
      }
    } catch (e) {
      _errorMessage = e.toString();
    } finally {
      _setLoading(false);
    }
  }

  Future<void> logout() async {
    _setLoading(true);

    try {
      await ApiService.logout();
      _user = null;
      await _clearUserFromPrefs();
    } catch (e) {
      _errorMessage = e.toString();
    } finally {
      _setLoading(false);
    }
  }

  Future<void> checkAuthStatus() async {
    try {
      await ApiService.init();
      final response = await ApiService.getCurrentUser();
      if (response['success']) {
        _user = User.fromJson(response['data']['user']);
      }
    } catch (e) {
      // User not authenticated, clear any stored data
      await _clearUserFromPrefs();
    }
  }

  void _setLoading(bool loading) {
    _isLoading = loading;
    notifyListeners();
  }

  void clearError() {
    _errorMessage = null;
    notifyListeners();
  }

  Future<void> _saveUserToPrefs() async {
    if (_user != null) {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('user_name', _user!.name);
      await prefs.setString('user_email', _user!.email);
      await prefs.setInt('user_id', _user!.id);
    }
  }

  Future<void> _clearUserFromPrefs() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('user_name');
    await prefs.remove('user_email');
    await prefs.remove('user_id');
  }
}
```

## Usage Examples

### Login Screen

```dart
class LoginScreen extends StatefulWidget {
  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _obscurePassword = true;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Login')),
      body: Consumer<AuthProvider>(
        builder: (context, authProvider, child) {
          if (authProvider.isLoading) {
            return Center(child: CircularProgressIndicator());
          }

          return Padding(
            padding: EdgeInsets.all(16.0),
            child: Form(
              key: _formKey,
              child: Column(
                children: [
                  TextFormField(
                    controller: _emailController,
                    decoration: InputDecoration(labelText: 'Email'),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Please enter your email';
                      }
                      return null;
                    },
                  ),
                  TextFormField(
                    controller: _passwordController,
                    decoration: InputDecoration(
                      labelText: 'Password',
                      suffixIcon: IconButton(
                        icon: Icon(
                          _obscurePassword
                              ? Icons.visibility
                              : Icons.visibility_off,
                        ),
                        onPressed: () {
                          setState(() {
                            _obscurePassword = !_obscurePassword;
                          });
                        },
                      ),
                    ),
                    obscureText: _obscurePassword,
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Please enter your password';
                      }
                      return null;
                    },
                  ),
                  if (authProvider.errorMessage != null)
                    Padding(
                      padding: EdgeInsets.symmetric(vertical: 8.0),
                      child: Text(
                        authProvider.errorMessage!,
                        style: TextStyle(color: Colors.red),
                      ),
                    ),
                  SizedBox(height: 20),
                  ElevatedButton(
                    onPressed: () async {
                      if (_formKey.currentState!.validate()) {
                        await authProvider.login(
                          _emailController.text,
                          _passwordController.text,
                        );
                        if (authProvider.isAuthenticated) {
                          Navigator.pushReplacementNamed(context, '/home');
                        }
                      }
                    },
                    child: Text('Login'),
                  ),
                  TextButton(
                    onPressed: () {
                      Navigator.pushNamed(context, '/register');
                    },
                    child: Text('Don\'t have an account? Register'),
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}
```

### Income List Screen

```dart
class IncomeListScreen extends StatefulWidget {
  @override
  _IncomeListScreenState createState() => _IncomeListScreenState();
}

class _IncomeListScreenState extends State<IncomeListScreen> {
  List<Income> _incomeList = [];
  bool _isLoading = false;
  int _currentPage = 1;
  bool _hasNextPage = true;

  @override
  void initState() {
    super.initState();
    _loadIncome();
  }

  Future<void> _loadIncome() async {
    if (_isLoading || !_hasNextPage) return;

    setState(() => _isLoading = true);

    try {
      final response = await ApiService.getIncome(page: _currentPage);
      if (response['success']) {
        final List<dynamic> incomeData = response['data']['income'];
        final Map<String, dynamic> pagination = response['data']['pagination'];

        setState(() {
          _incomeList.addAll(
            incomeData.map((item) => Income.fromJson(item)).toList(),
          );
          _currentPage++;
          _hasNextPage = _currentPage <= pagination['last_page'];
        });
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: ${e.toString()}')),
      );
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Income'),
        actions: [
          IconButton(
            icon: Icon(Icons.add),
            onPressed: () => _navigateToAddIncome(),
          ),
        ],
      ),
      body: _incomeList.isEmpty && !_isLoading
          ? Center(
              child: Text('No income records found'),
            )
          : NotificationListener<ScrollNotification>(
              onNotification: (scrollInfo) {
                if (scrollInfo.metrics.pixels ==
                    scrollInfo.metrics.maxScrollExtent) {
                  _loadIncome();
                }
                return false;
              },
              child: ListView.builder(
                itemCount: _incomeList.length + (_isLoading ? 1 : 0),
                itemBuilder: (context, index) {
                  if (index == _incomeList.length && _isLoading) {
                    return Center(child: CircularProgressIndicator());
                  }

                  final income = _incomeList[index];
                  return IncomeTile(income: income);
                },
              ),
            ),
      floatingActionButton: FloatingActionButton(
        onPressed: _navigateToAddIncome,
        child: Icon(Icons.add),
      ),
    );
  }

  void _navigateToAddIncome() {
    Navigator.pushNamed(context, '/add_income');
  }
}

class IncomeTile extends StatelessWidget {
  final Income income;

  const IncomeTile({Key? key, required this.income}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Card(
      child: ListTile(
        leading: CircleAvatar(
          child: Icon(Icons.arrow_upward, color: Colors.white),
          backgroundColor: Colors.green,
        ),
        title: Text(income.description),
        subtitle: Text(
          '${income.category.name} • ${income.date.toString().split(' ')[0]}',
        ),
        trailing: Text(
          'Rp ${income.amount.toString()}',
          style: TextStyle(
            fontWeight: FontWeight.bold,
            color: Colors.green,
          ),
        ),
      ),
    );
  }
}
```

## Testing the API

### Using Postman

1. Import the `postman_collection.json` file into Postman
2. Set the environment variables:
   - `baseUrl`: `http://localhost:8000/api`
   - `token`: Will be automatically set after login
3. Test endpoints in order:
   - Register → Login → Get User → Create Income → Get Income List

### Using cURL

```bash
# Register
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'

# Get Income (replace TOKEN with actual token)
curl -X GET http://localhost:8000/api/income \
  -H "Authorization: Bearer TOKEN" \
  -H "Accept: application/json"
```

## Important Notes

### Security
- Always validate user inputs before sending to API
- Store tokens securely using Flutter's secure storage mechanisms
- Never log sensitive information
- Use HTTPS in production environments

### Error Handling
- Always check the `success` field in API responses
- Handle network timeouts and connection errors
- Show user-friendly error messages
- Implement retry logic for failed requests

### Performance
- Implement pagination for large datasets
- Use caching for frequently accessed data
- Consider implementing offline data storage
- Optimize image and file uploads

### Date/Time Handling
- API returns dates in ISO 8601 format
- Convert to local timezone for display
- Handle timezone differences properly
- Format dates according to user preferences

This setup provides a solid foundation for integrating the Money Management API with your Flutter application. Remember to test all endpoints thoroughly and handle edge cases appropriately.