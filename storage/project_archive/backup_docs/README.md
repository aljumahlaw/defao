# Document Management System

A modern document management system built with Laravel 11, Livewire 3, and Tailwind CSS.

## 🚀 Features

- **Document Management**: Upload, organize, and track documents through workflow stages
- **Task Management**: Create and assign tasks related to documents
- **User Management**: Role-based access control with permissions
- **Activity Logging**: Track all document activities
- **Real-time Search**: Global search across documents and tasks
- **Responsive Design**: Modern UI with dark mode support

## 📋 Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- PostgreSQL (for production) or SQLite (for development)

## 🛠️ Installation

### Local Development

1. Clone the repository:
```bash
git clone <repository-url>
cd Master
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node dependencies:
```bash
npm install
```

4. Copy environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Run migrations:
```bash
php artisan migrate
```

7. Seed database:
```bash
php artisan db:seed
```

8. Create storage link:
```bash
php artisan storage:link
```

9. Build assets:
```bash
npm run build
```

10. Start development server:
```bash
php artisan serve
```

Visit `http://127.0.0.1:8000`

## 🔑 Default Login (Development)

- **Email**: `admin@example.com`
- **Password**: `password`

If the admin user does not exist, you can create it with:
```bash
php artisan user:create-admin
```

For more detailed login and user creation instructions, see `Archive/HOW_TO_LOGIN.md`.

## 🚂 Railway Deployment

See `railway-setup.md` for detailed deployment instructions.

Quick steps:
1. Create Railway project
2. Add PostgreSQL database
3. Set environment variables (see `RAILWAY_ENV_TEMPLATE.md`)
4. Connect Git repository
5. Deploy!

## 📁 Project Structure

```
app/
├── Livewire/          # Livewire components
│   ├── Documents/     # Document management
│   ├── Tasks/         # Task management
│   └── Profile/       # User profile
├── Models/            # Eloquent models
└── Http/              # Controllers

resources/
├── views/             # Blade templates
└── css/              # Tailwind CSS

database/
├── migrations/        # Database migrations
└── seeders/          # Database seeders
```

## 🔧 Configuration

### Database

The application uses PostgreSQL in production and SQLite in development.

### Storage

- **Development**: Local storage (`storage/app`)
- **Production**: Use S3 (configure in `.env`)

### Environment Variables

See `RAILWAY_ENV_TEMPLATE.md` for required environment variables.

## 📚 Documentation

- `railway-setup.md` - Railway setup guide
- `DEPLOYMENT_CHECKLIST.md` - Deployment checklist
- `RAILWAY_ENV_TEMPLATE.md` - Environment variables template

For detailed architecture, build and deployment documents, see the `Archive/` folder.

## 🧪 Testing

```bash
php artisan test
```

## 📝 License

MIT License

---

**✅ تم تنظيف المشروع - جاهز للـ Deploy**

