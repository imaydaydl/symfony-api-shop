# Використовуємо офіційний образ PHP з Apache
FROM php:8.1-apache

# Копіюємо конфігураційний файл Apache в контейнер
COPY apache2.conf /etc/apache2/sites-available/localhost.conf
RUN ln -s /etc/apache2/sites-available/localhost.conf /etc/apache2/sites-enabled/localhost.conf

# Налаштування веб-сервера Apache
RUN a2enmod rewrite

# Перезавантажуємо Apache, щоб застосувати зміни
RUN service apache2 restart

# Встановлюємо деякі залежності та розширення PHP
RUN apt-get update && apt-get install -y \
    bash \
    git \
    unzip \
    libicu-dev \
    zlib1g-dev \
    libzip-dev \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql intl zip

# Встановлюємо Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Встановлюємо Symfony cli
RUN curl -sS https://get.symfony.com/cli/installer | bash

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Вказуємо робочу директорію
WORKDIR /var/www/html

# Копіюємо файли проекту до контейнера
RUN git clone https://github.com/imaydaydl/symfony-api-shop.git /var/www/html

# Встановлюємо залежності Composer
RUN composer install

# Встановлення дозволів на файли Symfony
RUN chown -R www-data:www-data var

RUN php bin/console regenerate-app-secret

# Запускаємо веб-сервер Apache
CMD ["apache2-foreground"]