# Mobile Starter

This is an optional Flutter starter for SaaSKit Lite. It is intentionally small and focused on the first integration points:

- Login against the Laravel Sanctum API
- In-memory token handling for local demos
- Dashboard stats screen

## Setup
1. Install Flutter 3.x.
2. Update the API base URL in `lib/src/services/api_service.dart` if needed.
3. Install dependencies:
   ```bash
   flutter pub get
   ```
4. Run the app:
   ```bash
   flutter run
   ```

## Notes
- Android emulators usually reach the host machine through `10.0.2.2`.
- This starter keeps token handling simple on purpose. For production, add secure storage and refresh flows.
