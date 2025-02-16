# BMW Parts Store - Laravel Backend API

## Project Overview
This project is a RESTful API built using Laravel 10+ for a hypothetical BMW Parts Store. The API provides CRUD operations for managing products, categories, orders, and users, with role-based and permission-based access control. The API is secured using Laravel Sanctum for token-based authentication.

The project follows a **Clean Architecture** approach, organized into the following layers:
- **Application Layer**: Contains use cases and application logic.
- **Domain Layer**: Includes contracts (interfaces) and entities.
- **Infrastructure Layer**: Implements repositories using Eloquent models.
- **Presentation Layer**: Handles HTTP requests and responses via controllers.

## Business Scenario
The BMW Parts Store is an online platform where users can browse and purchase car parts. The API supports the following resources:
- **Products**: Represents the car parts available in the store.
- **Categories**: Represents product categories.
- **Orders**: Represents customer orders.
- **Order Items**: Represents individual items within an order.
- **Users**: Represents the users of the system (admin, manager, basic_user).

## Features
- **CRUD Operations**: Create, Read, Update, and Delete operations for products, categories, orders, and users.
- **Authentication & Authorization**: Token-based authentication using Laravel Sanctum. Role-based and permission-based access control.
- **Error Handling**: Proper validation and error handling with meaningful error messages and appropriate HTTP status codes.
- **Unit Tests**: Includes unit tests for use cases such as `OrderItemUseCaseTest`, `CartUseCaseTest`, `OrderUseCaseTest`, and `UserUseCaseTest`.

## Technology Stack
- **Laravel 10+**
- **PostgreSQL** (Relational Database)
- **Laravel Sanctum** (Authentication)
- **Composer** (Dependency Management)

## Project Structure
The project is organized into the following directories:
- **Application**: Contains use cases and application-specific logic.
- **Domain**: Includes contracts (interfaces) and entities.
- **Infra**: Implements repositories using Eloquent models.
- **Presentation**: Handles HTTP requests and responses via controllers.

## Additional Libraries/Packages
- **Laravel Sanctum**: For token-based authentication.
- **Laravel Gates**: For role-based and permission-based access control.
- **Laravel Seeders**: For generating dummy data for testing.

## Project Setup

### Prerequisites
- PHP 8.1 or higher
- Composer
- PostgreSQL
- Laravel CLI

### Installation
Install dependencies: composer install

Create a .env file and configure your environment variables: cp .env.example .env

Update the following variables in the .env file:
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=bmw_parts_store
DB_USERNAME=your_username
DB_PASSWORD=your_password

Run migrations and seeders: php artisan migrate --seed

To run the unit tests, use the following command: php artisan test
