---
**Updated:** 2025-12-22 - Defao v1.0.1  
**Status:** ✅ Production Ready  
**Features:** Workflow, Reports link, Arabic toasts  
---

# Railway Deployment Guide

## 📋 Prerequisites

1. Railway account: https://railway.app
2. Git repository (GitHub/GitLab/Bitbucket)
3. PostgreSQL database (Railway provides this)

## 🚀 Step 1: Create Railway Project

1. Go to https://railway.app
2. Click "New Project"
3. Select "Deploy from GitHub repo" (or your Git provider)
4. Connect your repository
5. Select the repository branch (usually `main` or `master`)

## 🗄️ Step 2: Add PostgreSQL Database

1. In your Railway project, click "+ New"
2. Select "Database" → "PostgreSQL"
3. Railway will automatically create a PostgreSQL database
4. The `DATABASE_URL` environment variable will be automatically set

## ⚙️ Step 3: Configure Environment Variables

Go to your service → Variables tab and add:

```env
APP_NAME="Document Management System"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.railway.app

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=pgsql
# DATABASE_URL is automatically set by Railway PostgreSQL service

FILESYSTEM_DISK=public

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Generate APP_KEY

Run locally:
```bash
php artisan key:generate --show
```

Copy the output and paste it as `APP_KEY` in Railway.

## 🔧 Step 4: Build Configuration

Railway will automatically detect:
- `composer.json` → Install PHP dependencies
- `package.json` → Install Node dependencies
- `railway.json` or `railway.toml` → Build configuration

The build process will:
1. Install PHP dependencies (`composer install --no-dev --optimize-autoloader`)
2. Install Node dependencies (`npm install`)
3. Build assets (`npm run build`)
4. Optimize Laravel (`php artisan optimize`)

## 🗃️ Step 5: Database Migration & Seeding

Railway will automatically run migrations on deploy. To manually run:

1. Go to your service → Deployments
2. Click on the latest deployment
3. Open "Deploy Logs"
4. Or use Railway CLI:

```bash
railway run php artisan migrate --force
railway run php artisan db:seed --force
```

## 📦 Step 6: Storage Link

Create storage symlink:

```bash
railway run php artisan storage:link
```

Or add to your deployment script.

## 🌐 Step 7: Custom Domain (Optional)

1. Go to your service → Settings
2. Click "Generate Domain" for Railway subdomain
3. Or add custom domain:
   - Click "Custom Domain"
   - Enter your domain
   - Add DNS records as instructed
   - SSL certificate is automatically provisioned

## ✅ Step 8: Verify Deployment

1. Visit your Railway URL: `https://your-app.railway.app`
2. Check logs: Service → Deployments → Latest → Logs
3. Test the application:
   - Register/Login
   - Upload document
   - Create task
   - Check database

## 🔍 Troubleshooting

### Database Connection Issues

Check `DATABASE_URL` is set correctly:
```bash
railway variables
```

### Build Failures

Check build logs:
- Service → Deployments → Latest → Build Logs
- Common issues:
  - Missing PHP extensions
  - Node version mismatch
  - Memory limits

### 500 Errors

Check application logs:
```bash
railway logs
```

Enable debug temporarily:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

### Storage Issues

Ensure storage is writable:
```bash
railway run php artisan storage:link
```

## 📊 Monitoring

- **Metrics**: Service → Metrics (CPU, Memory, Network)
- **Logs**: Service → Deployments → Latest → Logs
- **Database**: PostgreSQL service → Data → Query logs

## 🔄 Continuous Deployment

Railway automatically deploys on:
- Push to connected branch
- Manual trigger from dashboard

## 🛠️ Railway CLI

Install Railway CLI:
```bash
npm i -g @railway/cli
```

Login:
```bash
railway login
```

Link project:
```bash
railway link
```

Run commands:
```bash
railway run php artisan migrate
railway run php artisan tinker
railway logs
```

## 📝 Notes

- Railway provides free PostgreSQL (500MB)
- Storage is ephemeral (use S3 for persistent storage)
- Auto-scaling based on traffic
- SSL certificates are automatic
- Environment variables are encrypted

