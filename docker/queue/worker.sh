
#!/bin/sh
echo "Avvio queue worker..."
php /var/www/html/artisan queue:work redis \
    --sleep=3 \
    --tries=3 \
    --backoff=60 \
    --max-time=3600