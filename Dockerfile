FROM php:8.2-fpm
WORKDIR /var/www
RUN apt-get update && apt-get install -y \
	libpng-dev \
	libjpeg62-turbo-dev \
	libfreetype6-dev \
	libonig-dev \
	libzip-dev \
	zip \
	unzip \
	git \
	curl

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install --optimize-autoloader --no-dev

COPY --chown=www-data:www-data . /var/www

EXPOSE 9000
CMD ["php-fpm"]
