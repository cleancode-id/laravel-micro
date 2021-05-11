FROM alpine:3.13

LABEL Maintainer="Yoga Hanggara <yohang88@gmail.com>" \
      Description="Lightweight Laravel app container with Nginx 1.18 & PHP-FPM 8 based on Alpine Linux."

ARG PHP_VERSION="8.0.2-r0"

# Install packages
RUN apk --no-cache add php8=${PHP_VERSION} php8-fpm php8-opcache php8-openssl php8-curl php8-phar php8-session \
    php8-fileinfo php8-pdo php8-pdo_mysql php8-mysqli php8-mbstring php8-dom \
    nginx supervisor curl

# Symlink php8 => php
RUN ln -s /usr/bin/php8 /usr/bin/php

# Configure nginx
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf

# Remove default server definition
RUN rm /etc/nginx/conf.d/default.conf

# Configure PHP-FPM
COPY docker/php/fpm-pool.conf /etc/php8/php-fpm.d/www.conf
COPY docker/php/php.ini /etc/php8/conf.d/custom.ini

# Configure supervisord
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup document root
RUN mkdir -p /var/www/html
COPY docker/docker-entrypoint.sh docker-entrypoint.sh
RUN chmod +x docker-entrypoint.sh

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /var/www/html && \
  chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib/nginx && \
  chown -R nobody.nobody /var/log/nginx

# Switch to use a non-root user from here on
USER nobody

# Add application
WORKDIR /var/www/html
COPY --chown=nobody src/ /var/www/html

# Install composer from the official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Run composer install to install the dependencies
RUN composer install --no-cache --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-progress && \
    composer dump-autoload --optimize

# Expose the port nginx is reachable on
EXPOSE 8080

# Let start
ENTRYPOINT ["/bin/sh", "/docker-entrypoint.sh"]