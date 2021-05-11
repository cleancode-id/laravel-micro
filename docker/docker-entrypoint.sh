#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

if [ "$env" != "local" ]; then
    echo "Caching configuration..."
    php /var/www/html/artisan optimize
fi

if [ "$role" = "app" ]; then
    # Start supervisord
    echo "Starting supervisord..."
    /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

elif [ "$role" = "queue" ]; then
    # Start queue worker
    echo "Running the queue worker..."
    php /var/www/html/artisan queue:work --verbose --tries=3 --timeout=90

elif [ "$role" = "scheduler" ]; then
    # Start scheduler
    echo "Running scheduler..."
    while [ true ]
    do
        php /var/www/html/artisan schedule:run --verbose --no-interaction &
        sleep 60
    done

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
