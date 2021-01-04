Installation:

Copy files from repo
cd to project root & run >composer install
sqlite3 DB in /app folder -> imagesUpload.db
Adjust DB vars in parameters.yml if needed
Create tables in DB table from Entities >php bin/console doctrine:schema:update --force
Load fixtures >php bin/console doctrine:fixtures:load
For testing used PHPUnit Bridge, to run tests >./vendor/bin/simple-phpunit 2 tests, 3 assertions (AppBundle\Service\FileService tested while running Controller test)

Comments:

To run the app, register at /register, then login at /login
Uploading images accessed at /add

KnpPaginatorBundle is used in order not to load all rows on a single page
