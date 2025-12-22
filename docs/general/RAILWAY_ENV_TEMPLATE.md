<!-- Updated: 2025-12-22 v1.0.3 -->
---
**Updated:** 2025-12-22 - Defao v1.0.3  
**Status:** ✅ Production Ready  
**Features:** Workflow, Reports, Arabic toasts, Performance optimizations  
---

# Railway Environment Variables Template

Copy these variables to your Railway project → Service → Variables:

```env
# Application
APP_NAME="Defao"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.railway.app
APP_LOCALE=ar

# Database (DATABASE_URL is auto-set by Railway PostgreSQL)
DB_CONNECTION=pgsql

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error

# Filesystem
FILESYSTEM_DISK=public

# Cache & Sessions
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=sync

# Broadcasting
BROADCAST_DRIVER=log

# Mail (configure if needed)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# AWS S3 (for Phase 6 - file storage)
# AWS_ACCESS_KEY_ID=
# AWS_SECRET_ACCESS_KEY=
# AWS_DEFAULT_REGION=us-east-1
# AWS_BUCKET=
# AWS_URL=
```

## How to Get APP_KEY

Run locally:
```bash
php artisan key:generate --show
```

Copy the output and use it as `APP_KEY` value.

## Important Notes

1. **DATABASE_URL**: Automatically set by Railway when you add PostgreSQL service. Don't set manually.

2. **APP_URL**: Set to your Railway domain (e.g., `https://your-app.railway.app`)

3. **APP_DEBUG**: Always `false` in production

4. **FILESYSTEM_DISK**: Use `public` for Railway (ephemeral storage). For production, use S3.

5. **Storage**: Railway storage is ephemeral. Files will be lost on redeploy. Use S3 for persistent storage.

