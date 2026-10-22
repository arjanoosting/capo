SHELL=/usr/bin/env bash
.DEFAULT_GOAL := info
PHP_REFERENCE := php7.2
COMMANDPREFIX := sudo -u www-data
DEPLOYMENTDIR := /var/www/capo/

# Process all recipes in the current file looking for targets and comments with two hashtags
# parsing them into nicely formatted table
.PHONY: info
info:
	@grep -E '(^[/\.a-zA-Z_\-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/' && echo ""

##
## Internal tools

rsync: ## Sync to deployment folder
	@sudo mkdir -p ${DEPLOYMENTDIR}
	@sudo rsync -av ./ ${DEPLOYMENTDIR}
	@sudo chown -R www-data:www-data ${DEPLOYMENTDIR}

install: ## Run composer install
	@sudo mkdir -p ${DEPLOYMENTDIR}../.cache
	@sudo chmod 777 ${DEPLOYMENTDIR}../.cache
	@cd ${DEPLOYMENTDIR} ; ${COMMANDPREFIX} ${PHP_REFERENCE} composer1.phar install
	@cd ${DEPLOYMENTDIR} ; ${COMMANDPREFIX} ${PHP_REFERENCE} app/console assetic:dump --env=prod --no-debug

test: ## Run unit tests
	@cd ${DEPLOYMENTDIR}app ; ${COMMANDPREFIX} ${PHP_REFERENCE} ../vendor/bin/phpunit
