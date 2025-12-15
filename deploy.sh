#!/bin/bash

# Railway Deployment Script
# This script runs automatically on Railway deployment

set -e

echo "🚀 Starting Railway deployment..."

# Install dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "📦 Installing Node dependencies..."
npm ci --production

# Build assets
echo "🔨 Building assets..."
npm run build

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Seed database (only on first deploy or when needed)
# Uncomment if you want to seed on every deploy:
# php artisan db:seed --force

# Create storage link
echo "🔗 Creating storage symlink..."
php artisan storage:link || true

# Optimize Laravel
echo "⚡ Optimizing Laravel..."
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment complete!"
