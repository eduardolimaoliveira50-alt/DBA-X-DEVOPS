# Imagem para publicar o laboratório no Render (plano gratuito). Laravel 12 + PHP 8.3 + pdo_pgsql.
FROM php:8.3-cli-bookworm

RUN apt-get update \
 && apt-get install -y --no-install-recommends libpq-dev unzip git \
 && docker-php-ext-install pdo_pgsql \
 && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts --no-autoloader

COPY . .
RUN composer dump-autoload --optimize --no-dev \
 && php artisan package:discover --ansi \
 && chmod -R 777 storage bootstrap/cache

ENV APP_ENV=production APP_DEBUG=false LOG_CHANNEL=stderr \
    SESSION_DRIVER=cookie CACHE_STORE=array QUEUE_CONNECTION=sync DB_CONNECTION=pgsql
EXPOSE 10000
CMD ["bash", "scripts/render/start.sh"]
