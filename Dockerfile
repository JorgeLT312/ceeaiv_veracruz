FROM php:8.3-fpm

ARG user=ubuntu
ARG uid=1000

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nano

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure zip

RUN docker-php-ext-install zip

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN apt-get update && apt-get install -y libonig-dev && docker-php-ext-install mbstring

RUN docker-php-ext-install bcmath
RUN docker-php-ext-install exif
RUN docker-php-ext-install pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user


RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis


WORKDIR /var/www


COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini


# Installing Node.js and Npm
#RUN apt update && apt install -y nodejs npm

COPY startup.sh /var/www/startup.sh
RUN chmod +x /var/www/startup.sh
#ENTRYPOINT ["/var/www/startup.sh"]
USER $user
