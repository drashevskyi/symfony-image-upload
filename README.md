Installation:

1. Copy files from repo
2. cd to project root & run >composer install
3. sqlite3 DB in /app folder -> imagesUpload.db. Adjust DB vars in parameters.yml if needed
4. Create tables in DB table from Entities >php bin/console doctrine:schema:update --force
5. Load fixtures >php bin/console doctrine:fixtures:load
6. For testing used PHPUnit Bridge, to run tests >./vendor/bin/simple-phpunit 2 tests, 3 assertions (AppBundle\Service\FileService tested while running Controller test)

Comments:

When running the app locally, make sure antivirus does not block router.php, add exception if needed

To run the app, register at /register, then login at /login

Uploading images accessed at /add

KnpPaginatorBundle is used in order not to load all rows on a single page
