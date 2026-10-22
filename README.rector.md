## So you want to make this work locally

- have php of the corresponding version (7.4)
- composer v2 is used: the v1-only `robloach/component-installer` has been replaced by
  `composer/installers` + `oomphinc/composer-installers-extender`, which install the
  `components/*` packages into `web/components/` just the same
- run install with it as `sudo -u www-data php7.4 composer2.phar install`
- a few of the pinned dependencies predate php 7.4 and are patched on install by
  `cweagans/composer-patches`, see `patches/`
- this is a Symfony 2.8 application, which is long out of support, so a lot of the
  dependencies have open advisories. `config.policy.advisories.block` is turned off in
  `composer.json` because composer would otherwise refuse to resolve them at all. Run
  `php7.4 composer2.phar audit` to see the list
- if you page loads without stylesheets, that's because Assetic never generates files on the fly (for performance reasons).
- You must physically generate them via the terminal before the file can exist.
```
sudo -u www-data php7.4 app/console assetic:dump --env=prod --no-debug
```
- create database
```bash
# 2. Create the database if you haven't already
php7.4 app/console doctrine:database:create

# 3. Preview the SQL, then create the tables from the entities
php7.4 app/console doctrine:schema:update --dump-sql
php7.4 app/console doctrine:schema:create
php7.4 app/console doctrine:schema:update --force

# 4. Check that the mappings and database agree
php7.4 app/console doctrine:schema:validate
```
- create user
```bash
php7.4 app/console fos:user:create admin admin@example.com admin
php7.4 app/console fos:user:promote admin ROLE_ADMIN
```
- unit tests run as
```bash
# workdir
cd app

# load symfony stuff into sqlite
# if you forget --env=test - you are screwed
sudo -u www-data php7.4 console cache:clear --env=test
sudo -u www-data php7.4 console doctrine:schema:drop --force --env=test
sudo -u www-data php7.4 console doctrine:schema:create --env=test
sudo -u www-data php7.4 console doctrine:fixtures:load -n --env=test

# run the actual tests
sudo -u www-data php7.4 ../vendor/bin/phpunit
```
