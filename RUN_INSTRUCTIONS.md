# How to Run the KMF Website

## 1. Install PHP and MySQL

You need **PHP 7.4+** and **MySQL** (or MariaDB) on your machine.

### Option A: XAMPP (easiest on Windows)
1. Download [XAMPP](https://www.apachefriends.org/) and install.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. PHP will be at: `C:\xampp\php\php.exe`

### Option B: PHP only (for built-in server)
1. Download [PHP for Windows](https://windows.php.net/download/) (VS16 x64 Thread Safe).
2. Extract to e.g. `C:\php` and add `C:\php` to your system **PATH**.

## 2. Create the database

1. Open **phpMyAdmin** (http://localhost/phpmyadmin) if using XAMPP, or MySQL command line.
2. Create a database (or use existing). Then import the schema:
   - In phpMyAdmin: Import → choose `d:\kmf\database\schema.sql`
   - Or in terminal: `mysql -u root -p < d:\kmf\database\schema.sql`

3. Edit **`d:\kmf\config\database.php`** and set:
   - `DB_HOST` (usually `localhost`)
   - `DB_NAME` (e.g. `kmf_website`)
   - `DB_USER` (e.g. `root`)
   - `DB_PASS` (your MySQL password)

## 3. Start the PHP server

Open a terminal in the project folder and run:

```bash
php -S localhost:8080
```

**If PHP is not in PATH** (e.g. you use XAMPP), run:

```bash
C:\xampp\php\php.exe -S localhost:8080
```

Or double-click **`run-server.bat`** (edit the `PHP_BIN` line inside if needed).

## 4. Open the site

- **Website:** http://localhost:8080  
- **Admin CMS:** http://localhost:8080/admin/login.php  
  - Login: **admin** / **password** (change after first login)

## 5. Optional: Logo

Place your KMF logo at **`assets/images/logo.jpg`** or set the path in Admin → Settings → Logo URL.
