<?php

if (isset($_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'])) {
    passthru(sprintf(
        '"%s" "%s/console" cache:clear --env=%s --no-warmup',
        PHP_BINARY,
        __DIR__,
        $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV']
    ));
    passthru(sprintf(
        '"%s" "%s/console" doctrine:schema:create --env=test',
        PHP_BINARY,
        __DIR__
    ));
    passthru(sprintf(
        '"%s" "%s/console" doctrine:fixtures:load -n --env=test',
        PHP_BINARY,
        __DIR__
    ));
}

/* force loading of class */
if (class_exists('PHPUnit_Util_ErrorHandler', true)) {
}

require_once(__DIR__ . '/bootstrap.php.cache');
require_once(__DIR__ . '/../vendor/symfony/symfony/src/Symfony/Bridge/PhpUnit/bootstrap.php');
?>
