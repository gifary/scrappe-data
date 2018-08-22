Instalation
1. Copy .env.example to .env and Setup database in file .env

- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE=logique
- DB_USERNAME=root
- DB_PASSWORD=root

2. running composer install
3. running npm install
4. running bower install
5. running npm run dev
6. running php artisan migrate
7. running php artisan db:seed
8. running php artisan key:generate
9. running web with php artisan serve
10. running php artisan queue:work (for running queue)
# If getting some errrors try to fix with running composer dump-autoload
