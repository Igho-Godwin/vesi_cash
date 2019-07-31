# vesi_cash

Setup up your primary db,testing db name and username and password in .env and .env.testing respectfully

php artisan migrate to migrate tables to primary db

php artisan migrate --env=testing to migrate tables to testing db

I runned test on windows using. php vendor/phpunit/phpunit/phpunit

The test generates data and stores in public/data and public/edit and so on representing possible api urls.

The urls are passed to the function after json content is written to them.


