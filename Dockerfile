FROM php:8.2-apache

# تثبيت الأدوات الأساسية ومكتبات ونظام الـ PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# تثبيت Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# إعداد المجلد الرئيسي
WORKDIR /var/www/html
COPY . .

# تثبيت حزم لاراڤل وبناء الواجهة الأمامية
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# إعداد أذونات مجلدات التخزين والكاش
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# تعديل مسار أباتشي ليوجه إلى public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

EXPOSE 80

# تنفيذ المايجريشن إجبارياً قبل تشغيل الأباتشي
CMD php artisan migrate --force && apache2-foreground
