# Imagem para publicar o almoxarifado no Render (plano gratuito). Laravel 12 + PHP 8.3 + pdo_pgsql.

# 1. assets do painel (Vite): só o resultado (public/build) vai para a imagem final
FROM node:22-bookworm-slim AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY vite.config.js postcss.config.js ./
COPY resources ./resources
RUN npm run build

# 2. aplicação
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
COPY --from=assets /app/public/build ./public/build
RUN composer dump-autoload --optimize --no-dev \
 && php artisan package:discover --ansi \
 && chmod -R 777 storage bootstrap/cache

ENV APP_ENV=production APP_DEBUG=false LOG_CHANNEL=stderr APP_LOCALE=pt_BR APP_TIMEZONE=America/Cuiaba \
    SESSION_DRIVER=cookie CACHE_STORE=array QUEUE_CONNECTION=sync DB_CONNECTION=pgsql \
    PHP_CLI_SERVER_WORKERS=4
EXPOSE 10000
CMD ["bash", "scripts/render/start.sh"]
