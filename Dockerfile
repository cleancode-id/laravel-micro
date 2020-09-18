FROM jabardigitalservice/phpfpm-nginx:7.4

# Switch to root user
USER root

# Install additional packages
RUN apk --no-cache add php-pdo_mysql php-mysqli php-bcmath php-gmp php-zip

# Copy configurations
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/fpm-pool.conf /etc/php7/php-fpm.d/www.conf

# Switch to use a non-root user from here on
USER nobody

# Copy sources
COPY --chown=nobody . /var/www/html
COPY --chown=nobody .env.example /var/www/html/.env

# Install composer from the official image
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Run composer install to install the dependencies
RUN composer install --no-cache --prefer-dist --optimize-autoloader --no-interaction --no-progress

# RUN cd /var/www/html && \
    # php artisan route:cache
    # php artisan optimize
    # php artisan config:cache
