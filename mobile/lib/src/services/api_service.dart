import 'dart:convert';

import 'package:http/http.dart' as http;

class ApiService {
  ApiService({http.Client? client}) : _client = client ?? http.Client();

  static const String _baseUrl = 'http://10.0.2.2:8000/api/v1';

  final http.Client _client;
  String? _token;

  bool get isAuthenticated => _token != null;

  Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    final response = await _client.post(
      Uri.parse('$_baseUrl/auth/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'email': email,
        'password': password,
      }),
    );

    final payload = jsonDecode(response.body) as Map<String, dynamic>;

    if (response.statusCode >= 400 || payload['success'] != true) {
      final message = payload['message']?.toString() ?? 'Unable to login.';
      throw ApiException(message);
    }

    final data = payload['data'] as Map<String, dynamic>;
    _token = data['access_token'] as String?;

    return data;
  }

  Future<Map<String, dynamic>> fetchDashboard() async {
    final response = await _client.get(
      Uri.parse('$_baseUrl/dashboard'),
      headers: _authorizedHeaders,
    );

    final payload = jsonDecode(response.body) as Map<String, dynamic>;

    if (response.statusCode >= 400 || payload['success'] != true) {
      final message = payload['message']?.toString() ?? 'Unable to fetch dashboard.';
      throw ApiException(message);
    }

    return (payload['data'] as Map<String, dynamic>)['stats'] as Map<String, dynamic>;
  }

  Map<String, String> get _authorizedHeaders => {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $_token',
      };
}

class ApiException implements Exception {
  ApiException(this.message);

  final String message;

  @override
  String toString() => message;
}
