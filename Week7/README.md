# BIT3208 – Week 7: User Authentication and Session Management
## Faraj Fruit Supplier and Vendor

### Pages
- `index.php`      → Week 7 Home & Session Status
- `register.php`   → User Registration (password_hash)
- `login.php`      → User Login (password_verify + session)
- `dashboard.php`  → Protected Dashboard (session check)
- `logout.php`     → Logout (session_destroy)

### Key Concepts Demonstrated
1. password_hash() – secure password storage
2. password_verify() – password checking on login
3. $_SESSION – storing user data after login
4. session_start() – starting a PHP session
5. session_destroy() – ending a session on logout
6. Page protection – redirect if not logged in

### Setup
1. Copy folder to C:\xampp\htdocs\faraj\Week7\
2. Make sure faraj_db is running (users table must exist)
3. Visit http://localhost/faraj/Week7/

### Database Used
- Database: faraj_db
- Table: users (name, email, password, role)
