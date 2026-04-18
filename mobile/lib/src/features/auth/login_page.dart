import '../dashboard/dashboard_page.dart';
import '../../services/api_service.dart';
import 'package:flutter/material.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({super.key, required this.apiService});

  final ApiService apiService;

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _emailController = TextEditingController(text: 'admin@example.com');
  final _passwordController = TextEditingController(text: 'password');
  final _formKey = GlobalKey<FormState>();

  bool _isSubmitting = false;
  String? _errorMessage;

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }

    setState(() {
      _isSubmitting = true;
      _errorMessage = null;
    });

    try {
      final data = await widget.apiService.login(
        email: _emailController.text.trim(),
        password: _passwordController.text.trim(),
      );

      if (!mounted) {
        return;
      }

      await Navigator.of(context).pushReplacement(
        MaterialPageRoute(
          builder: (_) => DashboardPage(
            apiService: widget.apiService,
            user: data['user'] as Map<String, dynamic>,
          ),
        ),
      );
    } on ApiException catch (error) {
      setState(() {
        _errorMessage = error.message;
      });
    } finally {
      if (mounted) {
        setState(() {
          _isSubmitting = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: ConstrainedBox(
            constraints: const BoxConstraints(maxWidth: 420),
            child: Card(
              elevation: 0,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(28)),
              child: Padding(
                padding: const EdgeInsets.all(28),
                child: Form(
                  key: _formKey,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      const Text(
                        'SaaSKit Lite',
                        style: TextStyle(fontSize: 30, fontWeight: FontWeight.w700),
                      ),
                      const SizedBox(height: 8),
                      const Text(
                        'Log in with the seeded demo account to preview the starter app.',
                      ),
                      const SizedBox(height: 24),
                      TextFormField(
                        controller: _emailController,
                        decoration: const InputDecoration(
                          labelText: 'Email',
                          border: OutlineInputBorder(),
                        ),
                        validator: (value) {
                          if (value == null || value.trim().isEmpty) {
                            return 'Email is required.';
                          }

                          return null;
                        },
                      ),
                      const SizedBox(height: 16),
                      TextFormField(
                        controller: _passwordController,
                        obscureText: true,
                        decoration: const InputDecoration(
                          labelText: 'Password',
                          border: OutlineInputBorder(),
                        ),
                        validator: (value) {
                          if (value == null || value.trim().isEmpty) {
                            return 'Password is required.';
                          }

                          return null;
                        },
                      ),
                      if (_errorMessage != null) ...[
                        const SizedBox(height: 16),
                        Text(
                          _errorMessage!,
                          style: const TextStyle(color: Colors.redAccent),
                        ),
                      ],
                      const SizedBox(height: 24),
                      SizedBox(
                        width: double.infinity,
                        child: FilledButton(
                          onPressed: _isSubmitting ? null : _submit,
                          child: Padding(
                            padding: const EdgeInsets.symmetric(vertical: 14),
                            child: Text(_isSubmitting ? 'Signing in...' : 'Sign in'),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}
