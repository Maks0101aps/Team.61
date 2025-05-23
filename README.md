# Team.61     Project

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

#### 8. Start the development servers

In one terminal, start the Laravel server:

**Linux/Mac:**
```bash
php artisan serve
```

**Windows:**
```bash
php -S 127.0.0.1:8000 -t public
```

In another terminal, start the Vite development server:
```bash
npm run dev
```

#### 9. Access the application
Open your browser and navigate to:
```
http://localhost:8000
```

#### 10. Google Calendar Integration Setup
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google Calendar API
4. Create OAuth 2.0 credentials
5. Add your email to test users
6. Update .env file with your credentials:
```
GOOGLE_CALENDAR_CLIENT_ID=your_client_id
GOOGLE_CALENDAR_CLIENT_SECRET=your_client_secret
GOOGLE_CALENDAR_REDIRECT_URI=http://localhost/google-calendar/callback
GOOGLE_CALENDAR_ORIGIN=http://localhost
```

#### 11. Mailtrap Integration Setup

To test email sending functionality, update your `.env` file with your Mailtrap credentials (similar to Google Calendar):

```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=example@example.com
MAIL_FROM_NAME="School61"
```

> **Note:**  
> You can find your `MAIL_USERNAME` and `MAIL_PASSWORD` in your Mailtrap account.

### Build for Production
When you're ready to deploy:
```bash
npm run build
```

### Default Login Credentials

#### Teacher Account (Вчитель)
- **Email:** johndoe@example.com
- **Password:** secret

#### Parent Account (Батько)
- **Email:** janedoe@example.com
- **Password:** secret

#### Student Account (Студент)
- **Email:** jimmydoe@example.com
- **Password:** secret

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

