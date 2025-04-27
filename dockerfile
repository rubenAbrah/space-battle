FROM php:8.2-cli

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && docker-php-ext-install pcntl \
    && rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /app

# Копируем только файлы, необходимые для установки зависимостей
COPY composer.json composer.lock ./

# Установка зависимостей с dev-пакетами
RUN composer install --prefer-dist --no-progress --no-scripts

# Копируем исходный код
COPY . .

# Генерация автозагрузчика
RUN composer dump-autoload -o