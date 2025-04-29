FROM php:8.3-cli

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql mysqli

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /var/www

# Создаем необходимые директории
RUN mkdir -p storage bootstrap/cache

# Копируем только необходимые файлы
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-scripts --ignore-platform-reqs

# Копируем остальные файлы
COPY . .

# Устанавливаем правильные права
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Переключаемся на непривилегированного пользователя
USER www-data

# Запуск тестов (по умолчанию)
CMD ["composer", "test"]