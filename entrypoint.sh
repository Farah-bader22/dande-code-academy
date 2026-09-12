#!/bin/bash

# تشغيل الـ Migrations تلقائياً
php artisan migrate --force

# تنظيف وتخزين الكاش لبيئة الإنتاج
php artisan optimize:clear
php artisan config:cache
php artisan route:cache

# تشغيل السيرفر (الأباتشي)
exec apache2-foreground
