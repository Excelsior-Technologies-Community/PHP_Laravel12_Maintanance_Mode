# PHP_Laravel12_Maintanance_Mode

# Step 1: Install Laravel 12 Create Project

Run command:
```php
        Composer create –project laravel/laravel your folder name “^12.0”
```
# Step 2: Setup Database for .env file
```php
APP_MAINTENANCE_DRIVER=file

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=maintenance_db
DB_USERNAME=root
DB_PASSWORD=root123

SESSION_DRIVER=file

CACHE_STORE=file
QUEUE_CONNECTION=sync
```

# Step 3: Create Error folder 
```php
resources\views\errors
```

# Step 4 : Create Blade File

File Path:
```php
C:\xampp\htdocs\maintenance_project\resources\views\errors\maintenance.blade.php
```
```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Under Maintenance</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; }
        h1 { font-size: 50px; }
        body { font: 20px Helvetica, sans-serif; color: #333; }
        article { display: block; text-align: left; max-width: 650px; margin: 0 auto; }
        a { color: #dc8100; text-decoration: none; }
        a:hover { color: #333; text-decoration: none; }
    </style>
</head>
<body>
    <article>
        <h1>We'll be back soon!</h1>
        <div>
            <p>{{ $exception->getMessage() ?: 'Sorry for the inconvenience. We\'re performing some maintenance.' }}</p>
            <p>&mdash; The Team</p>
        </div>
    </article>
</body>
</html>
```

# Step 4: Run Laravel 12 Project

 Run:
 ```php
       php artisan serve
```
Output:
<img width="1170" height="614" alt="image" src="https://github.com/user-attachments/assets/3636d160-3229-40d2-9518-9fa4b43172e5" />

# Step 5: Enable Maintenance Mode
```php
php artisan down
```
<img width="1083" height="601" alt="image" src="https://github.com/user-attachments/assets/da7b7075-4cb2-4ff5-95b9-77946662678c" />

# Step 6: Disable Maintenance Mode
```php
php artisan up
```
<img width="1170" height="614" alt="image" src="https://github.com/user-attachments/assets/3636d160-3229-40d2-9518-9fa4b43172e5" />

# Project Folder Structure:

```php
PHP_Laravel12_Maintanance_Mode
├── app/
│   ├── Exceptions/
│   │   └── Handler.php
│
├── bootstrap/
│   └── app.php
│
├── resources/
│   └── views/
│       └── errors/
│           └── maintenance.blade.php
│
├── routes/
│   └── web.php
│
├── .env
│
└── artisan
```

