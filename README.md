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

Used Packages: 
- Laravel Roles & Permission (spatie/laravel-permission)
- Laravel Datatables (yajra/llaravel-datatables-oracle)

Project Setup:
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate --seed
- For use the test mail feature, sign up to Mailtrap.io then copy the SMTP setting to your .env and fill your email in MAIL_FROM_ADDRESS. 


Login: 
Email: admin1@gmail.com, 
Password: 123456
 
