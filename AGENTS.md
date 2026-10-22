## General rules

- Always use specific php binary name with version. Like `php7.2` or `php7.4`, assume it is installed on the system and kept up-to-speed with `update-alternatives`.

## Composer packages

With version 7.2 of php, use composer1, assume .phar files are already downloaded and placed in root folder

```bash
# for example
php7.2 composer1.phar update
```

## Unit testing

```bash
# change current dir to ./app
cd app

# reset cache if needed
php7.2 console cache:clear --env=test

# reset and recreate db with fixtures
php7.2 console doctrine:schema:drop --force --env=test
php7.2 console doctrine:schema:create --env=test
php7.2 console doctrine:fixtures:load -n --env=test

# run the unit tests
php7.2 ../vendor/bin/phpunit
```
