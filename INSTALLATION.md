# Installation
1. Run command `php artisan migrate:fresh --seed`
2. Run Shiled
   1. Generate permissions & policies : `php artisan shield:generate --all`
   2. Register super-admin : `php artisan shield:super-admin` or `php artisan shield:super-admin --user=2`
   3. If fail, do fresh installation : `php artisan shield:setup --fresh` or delete all files on Policies folder
3. Next...

php artisan migrate:fresh --seed && php artisan shield:generate --all && php artisan shield:super-admin --user=1