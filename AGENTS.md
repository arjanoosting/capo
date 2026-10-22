## General rules

- Always use specific php binary name with version. Like `php7.2` or `php7.4`, assume it is installed on the system and kept up-to-speed with `update-alternatives`.
- The project runs on php 7.4.

## Composer packages

With version 7.4 of php, use composer2, assume .phar files are already downloaded and placed in root folder

```bash
# for example
php7.4 composer2.phar update
```

Some dependencies are pinned to the last release that still works with Symfony 2.8 and
are too old for php 7.4. They are patched during install by `cweagans/composer-patches`,
the patches live in `patches/` and are listed under `extra.patches` in `composer.json`.

## Unit testing

```bash
# change current dir to ./app
cd app

# reset cache if needed
php7.4 console cache:clear --env=test

# reset and recreate db with fixtures
php7.4 console doctrine:schema:drop --force --env=test
php7.4 console doctrine:schema:create --env=test
php7.4 console doctrine:fixtures:load -n --env=test

# run the unit tests
php7.4 ../vendor/bin/phpunit
```
