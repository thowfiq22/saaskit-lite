import 'features/auth/login_page.dart';
import 'services/api_service.dart';
import 'package:flutter/material.dart';

class SaaSKitLiteApp extends StatelessWidget {
  const SaaSKitLiteApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'SaaSKit Lite',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: const Color(0xFF0F766E)),
        scaffoldBackgroundColor: const Color(0xFFF8FAFC),
        useMaterial3: true,
      ),
      home: LoginPage(apiService: ApiService()),
    );
  }
}
