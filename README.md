# Laravel 8 Roles & Permission

Case Study: School Dashboard

List: 
- Users
- Roles
- Students
- Rooms
- Articles

Constraint: 
- Users - Roles (one user only has one roles)
- Roles - Permissions (one role can has many permissions)
- Students - Rooms) (one student only has one room)
- Users - Articles (one user can has many articles)

Contains: 
- Laravel Roles & Permission (spatie/laravel-permission)
- Laravel Datatables (yajra/llaravel-datatables-oracle)

Project Setup:
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate --seed
