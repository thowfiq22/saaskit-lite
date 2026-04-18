import '../../services/api_service.dart';
import 'package:flutter/material.dart';

class DashboardPage extends StatelessWidget {
  const DashboardPage({
    super.key,
    required this.apiService,
    required this.user,
  });

  final ApiService apiService;
  final Map<String, dynamic> user;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Dashboard'),
      ),
      body: FutureBuilder<Map<String, dynamic>>(
        future: apiService.fetchDashboard(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }

          if (snapshot.hasError) {
            return Center(
              child: Padding(
                padding: const EdgeInsets.all(24),
                child: Text(snapshot.error.toString()),
              ),
            );
          }

          final stats = snapshot.data ?? <String, dynamic>{};

          return ListView(
            padding: const EdgeInsets.all(24),
            children: [
              Text(
                'Welcome, ${user['name']}',
                style: Theme.of(context).textTheme.headlineMedium,
              ),
              const SizedBox(height: 8),
              Text('Role: ${stats['role'] ?? user['role']}'),
              const SizedBox(height: 24),
              Wrap(
                spacing: 16,
                runSpacing: 16,
                children: [
                  _StatCard(
                    label: 'Products',
                    value: '${stats['products_total'] ?? 0}',
                    color: const Color(0xFF0F766E),
                  ),
                  _StatCard(
                    label: 'Active Products',
                    value: '${stats['active_products_total'] ?? 0}',
                    color: const Color(0xFF1D4ED8),
                  ),
                  if (stats['users_total'] != null)
                    _StatCard(
                      label: 'Users',
                      value: '${stats['users_total']}',
                      color: const Color(0xFFF97316),
                    ),
                ],
              ),
              const SizedBox(height: 24),
              const Text(
                'Recent Products',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.w700),
              ),
              const SizedBox(height: 12),
              ...((stats['latest_products'] as List<dynamic>? ?? const <dynamic>[])
                  .cast<Map<String, dynamic>>()
                  .map(
                    (product) => Card(
                      child: ListTile(
                        title: Text(product['name']?.toString() ?? 'Unknown product'),
                        subtitle: Text(product['sku']?.toString() ?? ''),
                        trailing: Text(
                          '${product['currency'] ?? 'USD'} ${product['price'] ?? ''}',
                        ),
                      ),
                    ),
                  )),
            ],
          );
        },
      ),
    );
  }
}

class _StatCard extends StatelessWidget {
  const _StatCard({
    required this.label,
    required this.value,
    required this.color,
  });

  final String label;
  final String value;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 220,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.circular(24),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(color: Colors.white70),
          ),
          const SizedBox(height: 12),
          Text(
            value,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 34,
              fontWeight: FontWeight.w700,
            ),
          ),
        ],
      ),
    );
  }
}
