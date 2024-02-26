# Використовуємо офіційний образ PHP з Apache
FROM php:8.1-apache

# Встановлюємо деякі залежності та розширення PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    mariadb-server \
    && docker-php-ext-install -j$(nproc) pdo_mysql pdo_pgsql intl

RUN systemctl start mariadb.service

# Встановлюємо Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Копіюємо файли проекту до контейнера
RUN git clone https://github.com/imaydaydl/symfony-api-shop /var/www/shop

# Встановлюємо залежності Composer
RUN composer install

# Виконуємо додаткові налаштування (за потреби)

# Вказуємо робочу директорію
WORKDIR /var/www/shop

RUN php bin/console doctrine:database:create
RUN php bin/console doctrine:migrations:migrate

# Виконуємо команду для запуску веб-сервера
CMD ["apache2-foreground"]