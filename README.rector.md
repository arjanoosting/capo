## So you want to make this work locally

- have php of the corresponding version (7.2 for the old snapshot)
- composer v2 will break with
```
robloach/component-installer 0.2.3 requires composer-plugin-api ^1.0 ->
found composer-plugin-api[2.9.0] but it does not match the constraint
```
- get composer v1 from `https://getcomposer.org/download/1.10.28/composer.phar`
- run install with it as `sudo -u www-data php7.2 composer1.phar install`
- if you page loads without stylesheets, that's because Assetic never generates files on the fly (for performance reasons).
- You must physically generate them via the terminal before the file can exist.
```
sudo -u www-data php7.2 app/console assetic:dump --env=prod --no-debug
```
- create database
```bash
# 2. Create the database if you haven't already
php7.2 app/console doctrine:database:create

# 3. Preview the SQL, then create the tables from the entities
php7.2 app/console doctrine:schema:update --dump-sql
php7.2 app/console doctrine:schema:create
php7.2 app/console doctrine:schema:update --force

# 4. Check that the mappings and database agree
php7.2 app/console doctrine:schema:validate
```
- create user
```bash
php7.2 app/console fos:user:create admin admin@example.com admin
php7.2 app/console fos:user:promote admin ROLE_ADMIN
```
- unit tests run as
```bash
# workdir
cd app

# load symfony stuff into sqlite
# if you forget --env=test - you are screwed
sudo -u www-data php7.2 console cache:clear --env=test
sudo -u www-data php7.2 console doctrine:schema:drop --force --env=test
sudo -u www-data php7.2 console doctrine:schema:create --env=test
sudo -u www-data php7.2 console doctrine:fixtures:load -n --env=test

# run the actual tests
sudo -u www-data php7.2 ../vendor/bin/phpunit
```
