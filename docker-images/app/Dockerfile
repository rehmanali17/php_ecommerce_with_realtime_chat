FROM php:8.0.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    apt-utils \
    procps \
    libpng-dev \
    libc-client-dev \
    libkrb5-dev \
    libzip-dev \
    libxml2-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip unzip \
    jpegoptim optipng pngquant gifsicle \
    vim

# Clear cache
RUN apt-get clean \
    && apt-get -y autoremove \
    && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install mysqli \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap \
    && docker-php-ext-install zip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install soap \
    && docker-php-ext-install exif \
    && docker-php-ext-install pcntl \
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/ \
    && docker-php-ext-install gd

    # strings
RUN apt-get update \
    && apt-get install -y libonig-dev \
    && docker-php-ext-install -j$(nproc) \
	    gettext \
	    mbstring


# images
RUN apt-get update \
	&& apt-get install -y \
	libfreetype6-dev \
	libjpeg62-turbo-dev \
	libpng-dev \
	libgd-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg

# ssh2
RUN apt-get update \
	&& apt-get install -y \
	libssh2-1-dev


WORKDIR /var/www