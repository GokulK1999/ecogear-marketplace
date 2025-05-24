# EcoGear Backend Setup

## Automatic Setup Instructions

This backend will work for anyone who clones the repository. Follow these simple steps:

### Prerequisites
- XAMPP installed (or any PHP/MySQL environment)
- PHP 7.4+ 
- MySQL 5.7+

### Quick Setup (5 minutes)

1. **Start XAMPP**
   - Start Apache and MySQL services

2. **Create Database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Click "Import" tab
   - Choose file: `backend/database/migrations.sql`
   - Click "Go" - Database will be created automatically!

3. **Test Backend**
   - Navigate to project root in terminal
   - Run: `php -S localhost:8000 -t backend/`
   - Backend API will be available at http://localhost:8000

4. **Test Frontend**
   - In another terminal: `npm run dev`
   - Frontend will be available at http://localhost:5173

### Features Included
- ✅ User registration and authentication
- ✅ Profile management
- ✅ Order management (CRUD operations)
- ✅ Sample data pre-loaded
- ✅ CORS headers configured
- ✅ Secure password hashing
- ✅ Input validation and sanitization

### API Endpoints
- POST /api/auth/register.php - User registration
- POST /api/auth/login.php - User login
- GET /api/user/profile.php - Get user profile
- PUT /api/user/update.php - Update user profile
- GET /api/orders/list.php - Get user orders
- POST /api/orders/create.php - Create new order
- PUT /api/orders/update.php - Update order
- DELETE /api/orders/delete.php - Delete order

### Default Login Credentials
- Email: demo@ecogear.com
- Password: password

No complex configuration needed - everything works out of the box!