FROM php:8.3-fpm

# Instala dependências
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Extensões PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define diretório
WORKDIR /var/www

# Ajusta permissões automaticamente
RUN chown -R www-data:www-data /var/www

# Expõe a porta do PHP-FPM
CMD ["php-fpm"]

# cria usuário com UID 1000
RUN useradd -u 1000 -ms /bin/bash laravel

# define como dono
RUN chown -R laravel:laravel /var/www

# Instalar Node.js
RUN apt-get update && apt-get install -y curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# usa o usuário
USER laravel