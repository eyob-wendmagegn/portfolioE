FROM php:8.2-cli-alpine

# PostgreSQL እና አስፈላጊ የPHP ኤክስቴንሽኖችን መጫን
RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Composer መጫን
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# ፕሮጀክቱን ወደ ኮንቴይነሩ መቅዳት
COPY . .

# Composer dependencies መጫን
RUN composer install --no-dev --optimize-autoloader

# ፖርት ማዘጋጀት
EXPOSE 8080

# ሰርቨሩን ማስነሳት
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}