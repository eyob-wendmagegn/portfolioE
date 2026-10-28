FROM php:8.2-cli-alpine

RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

# Storage link እና ፈቃዶችን (permissions) ማዘጋጀት
RUN php artisan storage:link || true
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD php artisan storage:link && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}