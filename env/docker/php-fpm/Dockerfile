FROM php:7.2-fpm

ARG PSR_VERSION=1.0.0
ARG PHALCON_VERSION=4.0.5
ARG PHALCON_EXT_PATH=php7/64bits

# Install packages for required extensions
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    zip \
    unzip \
    libzip-dev \
    libz-dev \
    libpq-dev \
    libssl-dev \
    libmcrypt-dev \
    libicu-dev \
    libxml2-dev \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev\
    libfreetype6-dev\
    libmagickwand-dev \
    libc-client-dev \
    libkrb5-dev \
    libpq-dev\
    libjpeg62-turbo-dev \
    libxpm-dev \
    libvpx-dev \
    libpcre3-dev \
    iputils-ping \
    curl \
    vim \
    cron \
    supervisor \
  && rm -rf /var/lib/apt/lists/*

# Install required extensions
RUN docker-php-ext-install json && \
    docker-php-ext-install mbstring && \
    docker-php-ext-install xml && \
    docker-php-ext-install ctype && \
    docker-php-ext-install tokenizer && \
    docker-php-ext-install pdo && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install exif && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install opcache && \
    docker-php-ext-install zip && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/ && \
    docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) gd && \
    docker-php-ext-enable gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Phalcon
RUN set -xe && \
    # Download PSR, see https://github.com/jbboehr/php-psr
    curl -LO https://github.com/jbboehr/php-psr/archive/v${PSR_VERSION}.tar.gz && \
    tar xzf ${PWD}/v${PSR_VERSION}.tar.gz && \
    # Download Phalcon
    curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz && \
    tar xzf ${PWD}/v${PHALCON_VERSION}.tar.gz && \
    docker-php-ext-install -j $(getconf _NPROCESSORS_ONLN) \
        ${PWD}/php-psr-${PSR_VERSION} \
        ${PWD}/cphalcon-${PHALCON_VERSION}/build/${PHALCON_EXT_PATH} \
    && \
    # Remove all temp files
    rm -r \
        ${PWD}/v${PSR_VERSION}.tar.gz \
        ${PWD}/php-psr-${PSR_VERSION} \
        ${PWD}/v${PHALCON_VERSION}.tar.gz \
        ${PWD}/cphalcon-${PHALCON_VERSION} \
    && \
    php -m

# Check PHP version:
ARG PHP_VERSION=${PHP_VERSION}
RUN php -v | head -n 1 | grep -q "PHP ${PHP_VERSION}."

# Copy PHP configuration files
COPY ./php.ini /usr/local/etc/php/

USER root

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

RUN usermod -u 1000 www-data

VOLUME ["/var/www/mdev"]

WORKDIR /var/www/mdev

