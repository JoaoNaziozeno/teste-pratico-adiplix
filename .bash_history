chmod -R 775 storage bootstrap/cache
ls -l storage
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache
touch storage/framework/views/teste.txt
chmod -R 777 storage bootstrap/cache
touch storage/framework/views/teste.txt
docker exec -it laravel_app bash
touch storage/framework/views/teste.txt
touch storage/framework/views/teste.txt
docker compose down -v
