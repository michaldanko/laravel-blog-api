# Laravel Blog API

A Laravel-based blog application with posts, categories, and authors. This application includes full API functionality with authentication, caching, and rate limiting.

## Features

- Manage Posts, Categories, and Authors.
- Authentication using Laravel Sanctum.
- Rate limiting: Max 60 requests per minute.
- JSON API endpoints.
- Basic caching for improved performance.
- Automated tests for API endpoints.
- API documentation with Swagger.

## Installation

### Requirements

Ensure you have the following installed on your system:

- PHP >= 8.1
- Composer
- MySQL or compatible database
- Laravel 10.x
- Node.js & npm (optional, for frontend assets)

### Steps to Set Up

#### 1. Clone the Repository

```
git clone https://github.com/michaldanko/laravel-blog-api.git laravel-blog-api
cd laravel-blog-api
```

#### 2. Install Dependencies

```
composer install
```

#### 3. Environment Configuration

Copy the .env.example file and configure it:

```
cp .env.example .env
php artisan key:generate
```

Update the .env file with your database credentials.

#### 4. Database Migration and Seeding

Run the migrations and seed the database with sample data:

```
php artisan migrate --seed
```

#### 5. Run the Application
Start the development server:

```
php artisan serve
```

#### 6. Access the Application
Visit `http://127.0.0.1:8000` to use the API.


## API Authentication

This application uses Laravel Sanctum for authentication. To interact with the API:

1. Register a user via `/api/register`.
2. Log in via `/api/login` to obtain a token.
3. Include the token in the `Authorization` header for subsequent API requests:

```
Authorization: Bearer YOUR_TOKEN
```

## API Documentation

Access the Swagger documentation at `http://127.0.0.1:8000/api/documentation`.

## Testing

Run the tests to ensure all endpoints function correctly:

```
php artisan test
```
