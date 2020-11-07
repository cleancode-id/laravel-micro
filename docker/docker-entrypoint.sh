#!/bin/sh

set -e

## Run Optimization
php /var/www/html/artisan optimize

# Start supervisord
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

