# 🏛️ Defao / DefandoDB - Legal Document & Task Management System

**A comprehensive LegalTech solution for law offices** | Laravel 11.47 + Livewire 3 + PostgreSQL 18

[![Laravel](https://img.shields.io/badge/Laravel-11.47-red.svg)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-3.4-orange.svg)](https://livewire.laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📊 Overview

Defao is a modern, production-ready document and task management system designed specifically for law offices. It provides a complete workflow management solution with document tracking, task assignment, archive management, and real-time collaboration features.

### Key Highlights

- ✅ **Production Ready** - 100% complete and tested
- ✅ **57 Active Documents** - Real-world data included
- ✅ **Real-time UX** - Livewire-powered reactive components
- ✅ **Clean Codebase** - 114 MB optimized project
- ✅ **Arabic/RTL Support** - Full right-to-left language support
- ✅ **Role-Based Access** - Spatie Permission integration

---

## ✨ Features

### 📋 Workflow Management

- **4-Stage Workflow**: Draft → Review1 → Proofread → FinalApproval
- **Real-time Stage Tracking**: Visual workflow overview with live updates
- **Stage Transitions**: Automated progression with user notifications
- **Overdue Detection**: Automatic tracking of overdue documents
- **Workflow Reports**: PDF export of workflow statistics

### 📄 Document Management

- **Document Upload**: Support for PDF, Word, and Excel files (up to 25MB)
- **Version Control**: Track multiple versions of documents
- **Advanced Filtering**: Filter by stage, type, date, assignee, and search
- **Bulk Actions**: Multi-select operations (archive, delete, change stage)
- **PDF Export**: Export filtered document lists as PDF
- **Document Archive**: Archive management with restore functionality

### 📝 Task Management

- **CRUD Operations**: Complete task creation, editing, and deletion
- **Task Assignment**: Assign tasks to specific users
- **Due Date Tracking**: Monitor task deadlines with overdue warnings
- **Status Management**: Open/closed status with toggle functionality
- **Document Tasks**: Link tasks to specific documents
- **Task Filtering**: Search and filter by status, priority, assignee

### 🗂️ Archive Management

- **Archive View**: Dedicated archive interface (`/documents/archive`)
- **Restore Functionality**: Unarchive documents when needed
- **Force Delete**: Permanent deletion with confirmation dialogs
- **Archive Filtering**: Search and date range filters

### 🛡️ Security & Permissions

- **Role-Based Access Control**: Admin, Authorized, and User roles
- **Document Policies**: Fine-grained permission system
- **Visible Scope**: Automatic filtering based on user permissions
- **Secure File Access**: Protected document viewing and downloading
- **CSRF Protection**: Built-in Laravel security features

### 🎨 User Experience

- **Responsive Design**: Mobile-first approach with desktop/mobile views
- **Dark Mode**: Full dark mode support across all components
- **Real-time Updates**: Livewire reactivity with instant feedback
- **Toast Notifications**: User-friendly action confirmations
- **Loading States**: Visual feedback during operations
- **Keyboard Shortcuts**: Quick navigation (Ctrl+D, Ctrl+T)

### 📊 Performance Optimizations

- **Caching**: Task list status counts (300s cache)
- **Database Indexes**: Optimized foreign keys and status columns
- **Eager Loading**: Conditional eager loading to prevent N+1 queries
- **Computed Properties**: Efficient query handling
- **Search Debounce**: 300ms debounce to reduce queries

---

## 🛠️ Tech Stack

### Backend
- **Laravel**: 11.47 (PHP 8.2+)
- **Livewire**: 3.4 (Server-side components)
- **PostgreSQL**: 18 (Database)
- **Redis**: Cache, Queue, and Session storage
- **Spatie Permission**: Role and permission management

### Frontend
- **Tailwind CSS**: 3.1+ (Utility-first CSS)
- **Alpine.js**: 3.4+ (Lightweight JavaScript framework)
- **Heroicons**: Icon library
- **Vite**: Build tool and asset bundler

### Additional Packages
- **DomPDF**: PDF generation and export
- **Laravel Breeze**: Authentication scaffolding
- **Moment.js**: Date manipulation (with Hijri calendar support)

---

## 📋 Requirements

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18+ and npm
- **PostgreSQL**: 14+ (production) or SQLite (development)
- **Redis**: For caching and queues (optional for development)

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd Master
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Database Setup

Configure your database in `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=defao
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Then run migrations and seeders:

```bash
php artisan migrate --seed
```

### 6. Storage Link

```bash
php artisan storage:link
```

### 7. Build Assets

```bash
npm run build
```

For development with hot reload:

```bash
npm run dev
```

### 8. Start Development Server

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` in your browser.

---

## 🔑 Default Login (Development)

After seeding the database, you can log in with:

- **Email**: `admin@example.com`
- **Password**: `password`

### Create Admin User Manually

If you need to create an admin user manually:

```bash
php artisan user:create-admin
```

Or use the helper script:

```bash
php Archive/create-admin-user.php
```

For detailed login and user creation instructions, see `Archive/HOW_TO_LOGIN.md`.

---

## 🔧 Configuration

### Database

The application supports multiple database drivers:

- **Production**: PostgreSQL (recommended)
- **Development**: SQLite (for quick setup)

### Storage

- **Development**: Local storage (`storage/app`)
- **Production**: Amazon S3 (configure in `.env`)

Example S3 configuration:

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
```

### Cache & Queue

For production, configure Redis:

```env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Environment Variables

See `Archive/RAILWAY_ENV_TEMPLATE.md` for a complete list of environment variables.

---

## 📁 Project Structure

```
app/
├── Livewire/
│   ├── Documents/        # Document management components
│   │   ├── DocumentTable.php
│   │   ├── DocumentDetail.php
│   │   ├── DocumentUpload.php
│   │   ├── DocumentArchive.php
│   │   └── DocumentTasks.php
│   ├── Tasks/            # Task management components
│   │   ├── TaskList.php
│   │   └── TaskForm.php
│   └── Workflow/         # Workflow components
│       ├── WorkflowOverview.php
│       └── WorkflowStageCard.php
├── Models/              # Eloquent models
│   ├── Document.php
│   ├── DocumentTask.php
│   ├── Task.php
│   └── User.php
└── Policies/            # Authorization policies
    └── DocumentPolicy.php

database/
├── migrations/          # Database migrations
└── seeders/            # Database seeders

resources/
├── views/
│   ├── livewire/       # Livewire component views
│   ├── documents/      # Document views
│   ├── tasks/          # Task views
│   ├── workflow/       # Workflow views
│   └── exports/        # PDF export templates
└── css/               # Tailwind CSS

routes/
└── web.php            # Web routes
```

---

## 🎯 Key Features Breakdown

### Workflow Stages

1. **Draft** (مسودة)
   - Initial document creation
   - Document upload and editing
   - Version management

2. **Review1** (مراجعة أولى)
   - First review stage
   - Comments and feedback
   - Version updates

3. **Proofread** (تدقيق)
   - Final proofreading
   - Quality checks
   - Final edits

4. **FinalApproval** (اعتماد نهائي)
   - Final approval stage
   - Read-only mode
   - Triggers automatic archiving

### Document Features

- **Upload**: Drag & drop or file picker
- **Preview**: PDF.js integration for document viewing
- **Versions**: Track all document versions
- **Comments**: Immutable comment system
- **Activity Log**: Complete audit trail
- **Sharing**: Secure signed routes for document sharing

### Task Features

- **Creation**: Rich task creation form
- **Assignment**: Assign to specific users
- **Due Dates**: Deadline tracking
- **Status**: Open/closed status management
- **Document Linking**: Link tasks to documents
- **Filtering**: Advanced search and filter options

---

## 🚢 Deployment

### Railway Deployment

See `railway-setup.md` for detailed Railway deployment instructions.

Quick steps:
1. Create Railway project
2. Add PostgreSQL database
3. Set environment variables (see `Archive/RAILWAY_ENV_TEMPLATE.md`)
4. Connect Git repository
5. Deploy!

### Production Optimization

Before deploying to production, run:

```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Or use the deployment script:

```bash
composer run deploy
```

### Deployment Checklist

See `DEPLOYMENT_CHECKLIST.md` for a complete deployment checklist.

---

## 🧪 Testing

Run the test suite:

```bash
php artisan test
```

The project includes:
- **Feature Tests**: 25 test files covering authentication, documents, tasks, and workflow
- **Unit Tests**: Model and component tests
- **Performance Tests**: Cache and query optimization tests
- **UX Tests**: User experience and interaction tests

---

## 📚 Documentation

### Main Documentation

- `PROJECT_A_TO_Z.md` - Complete project overview and features
- `DEPLOYMENT_CHECKLIST.md` - Production deployment checklist
- `railway-setup.md` - Railway deployment guide

### Archive Documentation

The `Archive/` folder contains historical documentation:

- `00_REQUIREMENTS_DOCUMENT.md` - Original requirements document
- `01_ARCHITECTURE_DESIGN.md` - Architecture and design docs
- `02_DATABASE_SCHEMA.md` - Database schema documentation
- `03_PRE_BUILD_CHECKLIST.md` - Pre-build checklist
- `04_COMMON_MISTAKES_SOLUTIONS.md` - Common issues and solutions
- `README_BUILD_GUIDE.md` - Detailed build guide
- `HOW_TO_LOGIN.md` - Login and user creation guide
- `QUICK_START.md` - Quick start guide

---

## 🎨 Design System

### Colors

- **Primary**: `#4C7FF1` (Blue)
- **Secondary**: `#4ECDC4` (Teal)
- **Success**: `#1FCDC7` (Green)
- **Warning**: `#FFC23A` (Yellow)
- **Error**: `#FF6AF2` (Pink)

### Typography

- **Font**: Noto Sans Arabic (RTL support)
- **Sizes**: Responsive scale from `text-sm` to `text-3xl`

### Icons

- **Library**: Heroicons (via `blade-ui-kit/blade-heroicons`)
- **Styles**: Outline (default), Solid (badges), Mini (inline)

---

## 🔐 Security Features

- ✅ **HTTPS Only** (production)
- ✅ **CSRF Protection** (Laravel built-in)
- ✅ **Signed Routes** (for document sharing)
- ✅ **Policy Classes** (Spatie Permission)
- ✅ **File Upload Validation** (type, size, name)
- ✅ **Rate Limiting** (API protection)
- ✅ **SQL Injection Protection** (Eloquent ORM)
- ✅ **XSS Protection** (Blade escaping)

---

## 📊 Project Statistics

- **Total Files**: 14,105 files
- **Project Size**: 114.54 MB
- **Database**: 57 documents (15 archived, 42 active)
- **Test Files**: 25 test files
- **Documentation**: 13 archive documentation files

---

## 🤝 Contributing

This is a private project for law offices. For questions or issues, please contact the project maintainer.

---

## 📝 License

MIT License - see LICENSE file for details.

---

## 🙏 Acknowledgments

- **Laravel** - The PHP framework
- **Livewire** - Server-side components
- **Tailwind CSS** - Utility-first CSS framework
- **Spatie** - Laravel Permission package
- **Heroicons** - Beautiful icon library

---

## 📞 Support

For detailed documentation and guides, see the `Archive/` folder.

**Defao - نظام إدارة وثائق ومهام قانونية متكامل** 🏛️

---

**✅ Production Ready - جاهز للعملاء الحقيقيين 🚀**
