# Shelluxe - Modern Bracelet E-commerce Platform

A full-stack e-commerce website for a local bracelet brand, built with HTML, CSS, PHP, and MySQL.

## Features

- 🛍️ Product browsing with categories and filters
- 👤 User authentication and profile management
- 🛒 Shopping cart functionality
- ❤️ Wishlist management
- 💳 Secure checkout process
- 📦 Order tracking and delivery status
- ⭐ Product ratings and reviews
- 🔍 Advanced search with auto-suggestions
- 📱 Responsive design for all devices

## Tech Stack

- Frontend: HTML5, CSS3, JavaScript
- Backend: PHP
- Database: MySQL
- Additional: Bootstrap 5, jQuery

## Prerequisites

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for PHP dependencies)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/shelluxe.git
cd shelluxe
```

2. Set up the database:
```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/seed.sql
```

3. Configure environment variables:
```bash
cp .env.example .env
```
Edit `.env` with your database credentials and other configurations.

4. Start your local server:
```bash
# If using XAMPP, place the project in htdocs directory
# If using PHP's built-in server:
php -S localhost:8000
```

5. Access the website at `http://localhost/shelluxe` or `http://localhost:8000`

## Project Structure

```
shelluxe/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── config/
│   └── database.php
├── database/
│   ├── schema.sql
│   └── seed.sql
├── includes/
│   ├── header.php
│   └── footer.php
├── admin/
│   └── dashboard.php
├── api/
│   └── endpoints/
├── vendor/
└── index.php
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request
