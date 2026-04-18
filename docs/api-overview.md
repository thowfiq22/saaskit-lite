# API Overview

Base URL:

```text
http://127.0.0.1:8000/api/v1
```

Authenticated requests use:

```text
Authorization: Bearer {token}
Accept: application/json
```

## Authentication

### Register
`POST /auth/register`

Request:
```json
{
  "name": "Jane Builder",
  "email": "jane@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

Response:
```json
{
  "success": true,
  "message": "Account created successfully.",
  "data": {
    "user": {
      "id": 5,
      "name": "Jane Builder",
      "email": "jane@example.com",
      "role": "user"
    },
    "access_token": "1|sanctum-token-value",
    "token_type": "Bearer"
  }
}
```

### Login
`POST /auth/login`

Request:
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

Response:
```json
{
  "success": true,
  "message": "Login successful.",
  "data": {
    "user": {
      "id": 1,
      "name": "SaaSKit Admin",
      "email": "admin@example.com",
      "role": "admin"
    },
    "access_token": "1|sanctum-token-value",
    "token_type": "Bearer"
  }
}
```

### Current User
`GET /auth/me`

Response:
```json
{
  "success": true,
  "message": "Authenticated user fetched successfully.",
  "data": {
    "user": {
      "id": 1,
      "name": "SaaSKit Admin",
      "email": "admin@example.com",
      "role": "admin"
    }
  }
}
```

### Logout
`POST /auth/logout`

Response:
```json
{
  "success": true,
  "message": "Logout successful.",
  "data": []
}
```

## Dashboard

### Fetch Dashboard Stats
`GET /dashboard`

Example response for an admin:
```json
{
  "success": true,
  "message": "Dashboard statistics fetched successfully.",
  "data": {
    "stats": {
      "role": "admin",
      "users_total": 4,
      "admins_total": 1,
      "products_total": 6,
      "active_products_total": 5,
      "latest_products": []
    }
  }
}
```

Example response for a regular user:
```json
{
  "success": true,
  "message": "Dashboard statistics fetched successfully.",
  "data": {
    "stats": {
      "role": "user",
      "products_total": 6,
      "active_products_total": 5,
      "latest_products": []
    }
  }
}
```

## Products

### List Products
`GET /products`

Query parameters:
- `search`: search by name, SKU, or description
- `is_active`: `1` or `0`
- `per_page`: page size from 1 to 100
- `sort_by`: `name`, `price`, `stock`, `created_at`, `updated_at`
- `sort_direction`: `asc` or `desc`

Example:
```text
GET /products?search=desk&is_active=1&per_page=10&sort_by=created_at&sort_direction=desc
```

Example response:
```json
{
  "success": true,
  "message": "Products fetched successfully.",
  "data": [
    {
      "id": 1,
      "name": "Starter Analytics",
      "description": "Analytics add-on for product metrics and engagement trends.",
      "sku": "SKU-ANALYTICS-001",
      "price": 49,
      "currency": "USD",
      "stock": 40,
      "is_active": true,
      "created_at": "2026-04-18T08:30:00+00:00",
      "updated_at": "2026-04-18T08:30:00+00:00",
      "creator": {
        "id": 1,
        "name": "SaaSKit Admin",
        "email": "admin@example.com"
      }
    }
  ],
  "meta": {
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 10,
      "total": 1,
      "from": 1,
      "to": 1,
      "has_more_pages": false
    }
  }
}
```

### Create Product
`POST /products`

Admin only.

Request:
```json
{
  "name": "Growth Dashboard",
  "description": "Module for growth reporting.",
  "sku": "SKU-GROWTH-100",
  "price": 129.99,
  "currency": "USD",
  "stock": 10,
  "is_active": true
}
```

Response:
```json
{
  "success": true,
  "message": "Product created successfully.",
  "data": {
    "product": {
      "id": 7,
      "name": "Growth Dashboard",
      "sku": "SKU-GROWTH-100",
      "currency": "USD"
    }
  }
}
```

### Update Product
`PUT /products/{id}`

Admin only.

At least one updatable field must be provided.

### Delete Product
`DELETE /products/{id}`

Admin only.

## Standard Response Format

### Success
```json
{
  "success": true,
  "message": "Operation completed successfully.",
  "data": {}
}
```

### Authentication Error
```json
{
  "success": false,
  "message": "Authentication is required to access this resource.",
  "errors": []
}
```

### Authorization Error
```json
{
  "success": false,
  "message": "You do not have permission to perform this action.",
  "errors": []
}
```

### Validation Error
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ]
  }
}
```

### Pagination Metadata
```json
{
  "meta": {
    "pagination": {
      "current_page": 1,
      "last_page": 2,
      "per_page": 10,
      "total": 15,
      "from": 1,
      "to": 10,
      "has_more_pages": true
    }
  }
}
```
