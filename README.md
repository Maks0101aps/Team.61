# School61 Project

## Installation Instructions

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js and npm
- Git

### Installation Steps

#### 1. Clone the repository
```bash
git clone https://github.com/Maks0101aps/school61project.git
cd school61project
```

#### 2. Install PHP dependencies
```bash
composer install
```

#### 3. Install NPM dependencies
```bash
npm install
```

#### 4. Set up environment file
Copy the example env file:

**Linux/Mac:**
```bash
cp .env.example .env
```

**Windows:**
```bash
copy .env.example .env
```

#### 5. Generate application key
```bash
php artisan key:generate
```

#### 6. Create SQLite database

**Linux/Mac:**
```bash
touch database/database.sqlite
```

**Windows PowerShell:**
```powershell
New-Item -Path "database\database.sqlite" -ItemType File -Force
```

#### 7. Run database migrations and seed data
```bash
php artisan migrate --seed
```

If you encounter a unique constraint violation when seeding, you can run:
```bash
php artisan db:seed
```

#### 8. Start the development servers

In one terminal, start the Laravel server:
```bash
php -S 127.0.0.1:8000 -t public
```

In another terminal, start the Vite development server:
```bash
npm run dev
```

#### 9. Access the application
- PHP backend: php -S 127.0.0.1:8000 -t public
- Vite development server: http://localhost:5173

### Default Login Credentials
- **Email:** johndoe@example.com
- **Password:** secret

### Build for Production
When you're ready to deploy:
```bash
npm run build
```

### Common Issues and Solutions

#### 1. Laravel command not found
Make sure you're in the project root directory and PHP is in your system PATH.

#### 2. Database file permission issues
Ensure your user has write permissions for the database directory.

#### 3. SQLite errors
If you're using SQLite and encounter errors, make sure SQLite extension is enabled in your php.ini.

#### 4. Dependencies not found
If Node.js or PHP dependencies aren't found, try clearing cache:
```bash
composer dump-autoload
npm cache clean --force
```

