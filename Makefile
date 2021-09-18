SHELL=bash

DOCKER_COMPOSE  = docker-compose

EXEC_JS         = $(DOCKER_COMPOSE) exec -T node entrypoint
EXEC_DB         = $(DOCKER_COMPOSE) exec -T database
EXEC_QA         = $(DOCKER_COMPOSE) run -T -e APP_ENV=test --rm php
EXEC_PHP        = $(DOCKER_COMPOSE) exec -e MAPS_USERNAME="Backer" -e MAPS_PASSWORD="EsterenBacker" -T php entrypoint

SYMFONY_CONSOLE = $(EXEC_PHP) php bin/console
COMPOSER        = $(EXEC_PHP) composer
YARN             = $(EXEC_JS) yarn

TEST_DBNAME = main_test
PORTAL_DBNAME = main
LEGACY_DBNAME = esteren_legacy
DB_USER = root
DB_PWD = root

CURRENT_DATE = `date "+%Y-%m-%d_%H-%M-%S"`

# Helper variables
_TITLE := "\033[32m[%s]\033[0m %s\n" # Green text
_ERROR := "\033[31m[%s]\033[0m %s\n" # Red text

##
## Project
## ───────
##

.DEFAULT_GOAL := help
help: ## Show this help message
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf " \033[32m%-25s\033[0m%s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help

install: build node_modules start vendor check-map-credentials db test-db legacy-db legacy-test-db get-maps-data assets ## Install and start the project
.PHONY: install

build:
	@$(DOCKER_COMPOSE) pull --include-deps
	@$(DOCKER_COMPOSE) build --force-rm --compress
.PHONY: build

start: ## Start all containers and the PHP server
	@$(DOCKER_COMPOSE) up -d --remove-orphans --no-recreate
	@$(MAKE) start-php
.PHONY: start

stop: ## Stop all containers and the PHP server
	@$(DOCKER_COMPOSE) stop
	@$(MAKE) stop-php
.PHONY: stop

restart: stop start ## Restart the containers & the PHP server
.PHONY: restart

kill:
	@$(DOCKER_COMPOSE) kill
.PHONY: kill

clean: ## Stop the project and remove generated files and configuration
	@printf $(_ERROR) "WARNING" "This will remove ALL containers, data, cache, to make a fresh project! Use at your own risk!"

	@if [[ -z "$(RESET)" ]]; then \
		printf $(_ERROR) "WARNING" "If you are 100% sure of what you are doing, re-run with \"$(MAKE) RESET=1 ...\"" ; \
		exit 0 ; \
	fi ; \
	\
	$(DOCKER_COMPOSE) down --volumes --remove-orphans \
	&& rm -rf \
		vendor \
		node_modules \
		data/map_data* \
		build/* \
		public/build \
		public/bundles \
		var/cache \
		var/log \
		var/sessions \
	\
	&& printf $(_TITLE) "OK" "Done!"
.PHONY: clean

full-reset: kill install ## Clean the project and start a fresh install of it
.PHONY: full-reset

##
## Tools
## ─────
##

cc: ## Clear and warmup PHP cache
	$(SYMFONY_CONSOLE) cache:clear --no-warmup
	$(SYMFONY_CONSOLE) cache:warmup
.PHONY: cc

db: dev-db migrations fixtures ## Reset the development database
.PHONY: db

dev-db: wait-for-db
	-$(SYMFONY_CONSOLE) doctrine:database:drop --if-exists --force
	-$(SYMFONY_CONSOLE) doctrine:database:create --if-not-exists

.PHONY: dev-db

test-db: wait-for-db ## Create a proper database for testing
	@echo "doctrine:database:drop"
	@APP_ENV=test $(SYMFONY_CONSOLE) --env=test doctrine:database:drop --if-exists --force
	@echo "doctrine:database:create"
	@APP_ENV=test $(SYMFONY_CONSOLE) --env=test doctrine:database:create
	@echo "doctrine:schema:create"
	@APP_ENV=test $(SYMFONY_CONSOLE) --env=test doctrine:migrations:migrate --no-interaction --allow-no-migration
	@echo "doctrine:mongodb:fixtures:load"
	@APP_ENV=test $(SYMFONY_CONSOLE) --env=test doctrine:mongodb:fixtures:load --append --no-interaction
.PHONY: test-db

legacy-db: wait-for-db ## Create a legacy database based on the provided sample
	@-APP_ENV=test $(SYMFONY_CONSOLE) doctrine:database:drop --connection=legacy --if-exists --force
	@-APP_ENV=test $(SYMFONY_CONSOLE) doctrine:database:create --connection=legacy

	@$(EXEC_DB) mysql -u$(DB_USER) -p$(DB_PWD) $(LEGACY_DBNAME) -e "source /srv/legacy_sample.sql"
.PHONY: legacy-db

legacy-test-db: wait-for-db ## Create a legacy database for testing
	@echo "doctrine:database:drop --connection=legacy"
	@-APP_ENV=test $(SYMFONY_CONSOLE) --env=test doctrine:database:drop --connection=legacy --if-exists --force
	@echo "doctrine:database:create --connection=legacy"
	@-APP_ENV=test $(SYMFONY_CONSOLE) --env=test doctrine:database:create --connection=legacy

	@$(EXEC_DB) mysql -u$(DB_USER) -p$(DB_PWD) test_$(LEGACY_DBNAME) -e "source /srv/legacy_sample.sql"
.PHONY: legacy-test-db

prod-db: var/dump.sql dev-db ## Installs production database if it has been saved in "var/dump.sql". You have to download it manually.
	@if [ -f var/dump.sql ]; then \
		$(EXEC_DB) mysql -u$(DB_USER) -p$(DB_PWD) $(PORTAL_DBNAME) -e "source /srv/dump.sql" ;\
	else \
		echo "No prod database to process. Download it and save it to var/dump.sql." ;\
	fi;
.PHONY: prod-db

var/dump.sql:
	@if [ "${CORAHNRIN_DEPLOY_REMOTE}" = "" ]; then \
		echo "[ERROR] Please specify the CORAHNRIN_DEPLOY_REMOTE env var to connect to a remote" ;\
		exit 1 ;\
	fi; \
	if [ "${CORAHNRIN_DEPLOY_DIR}" = "" ]; then \
		echo "[ERROR] Please specify the CORAHNRIN_DEPLOY_DIR env var to determine which directory to use in prod" ;\
		exit 1 ;\
	fi; \
	ssh ${CORAHNRIN_DEPLOY_REMOTE} ${CORAHNRIN_DEPLOY_DIR}/../dump_db.bash > var/dump.sql

migrations:
	-$(SYMFONY_CONSOLE) doctrine:migrations:migrate --no-interaction --allow-no-migration
.PHONY: migrations

fixtures:
	$(SYMFONY_CONSOLE) doctrine:mongodb:fixtures:load --append --no-interaction
.PHONY: fixtures

watch: ## Run Webpack to compile assets on change
	$(YARN) run watch
.PHONY: watch

assets: node_modules ## Run Webpack to compile assets
	@mkdir -p public/build/
	$(YARN) run dev
	@$(EXEC_PHP) php _dev_files/get_maps_assets.php
.PHONY: assets

vendor: ## Install PHP vendors
	$(COMPOSER) install
.PHONY: vendor

node_modules:
	@mkdir -p public/build/
	$(DOCKER_COMPOSE) run --rm --entrypoint=/bin/entrypoint node yarn install --unsafe-perm=true
	$(DOCKER_COMPOSE) up -d node
.PHONY: node_modules

wait-for-db:
	@echo " Waiting for database..."
	@for i in {1..5}; do $(EXEC_DB) mysql -u$(DB_USER) -p$(DB_PWD) -e "SELECT 1;" > /dev/null 2>&1 && sleep 1 || echo " Unavailable..." ; done;
.PHONY: wait-for-db

start-php:
	$(DOCKER_COMPOSE) up --force-recreate --no-deps -d php
.PHONY: start-php

stop-php:
	$(DOCKER_COMPOSE) stop php
.PHONY: stop-php

start-node:
	$(DOCKER_COMPOSE) up --force-recreate --no-deps -d node
.PHONY: start-node

##
## Tests
## ─────
##

ci-vendor:
	$(COMPOSER) install --no-interaction --classmap-authoritative --prefer-dist
.PHONY: ci-vendor

install-php: build start ci-vendor db test-db assets get-maps-data ## Prepare environment to execute PHP tests
.PHONY: install-php

install-node: build node_modules start-node ## Prepare environment to execute NodeJS tests
.PHONY: install-node

php-tests: start-php qa phpstan cs-dry-run phpunit ## Execute qa & tests
.PHONY: php-tests

phpstan: ## Execute phpstan
phpstan: start-php check-phpunit
	@echo "Clear & warmup test environment cache because phpstan may use it..."
	$(EXEC_QA) bin/console cache:clear --no-warmup
	$(EXEC_QA) bin/console cache:warmup
	$(EXEC_QA) phpstan analyse -c phpstan.neon
.PHONY: phpstan

check-phpunit:
	@$(EXEC_PHP) bin/phpunit --version
.PHONY: check-phpunit

cs: ## Execute php-cs-fixer
cs:
	$(EXEC_QA) php-cs-fixer fix
.PHONY: cs

cs-dry-run: ## Execute php-cs-fixer with a simple dry run
	$(EXEC_QA) php-cs-fixer fix --dry-run -vvv --diff --show-progress=dots
.PHONY: cs-dry-run

node-tests: start ## Execute checks & tests
	$(EXEC_JS) yarn run-script test --verbose -LLLL
.PHONY: node-tests

qa: ## Execute CS, linting, security checks, etc
	$(EXEC_QA) bin/console lint:twig templates src templates
	$(EXEC_QA) bin/console lint:yaml --parse-tags config translations
.PHONY: qa

setup-phpunit: check-phpunit

phpunit: check-phpunit ## Execute all PHPUnit tests
	$(EXEC_QA) bin/phpunit
.PHONY: phpunit

phpunit-unit: ## Execute all PHPUnit unit tests
	$(EXEC_QA) bin/phpunit --group=unit
.PHONY: phpunit-unit

phpunit-integration: ## Execute all PHPUnit integration tests
	$(EXEC_QA) bin/phpunit --group=integration
.PHONY: phpunit-integration

phpunit-functional: ## Execute all PHPUnit functional tests
	$(EXEC_QA) bin/phpunit --group=functional
.PHONY: phpunit-functional

phpunit-ux: ## Execute all PHPUnit ux tests
	$(EXEC_QA) bin/phpunit --group=ux
.PHONY: phpunit-ux

coverage: ## Retrieves the code coverage of the phpunit suite
	$(EXEC_QA) php -dextension=pcov -dpcov.enabled=1 bin/phpunit --coverage-html=build/coverage/$(CURRENT_DATE) --coverage-clover=build/coverage.xml
.PHONY: coverage

##
## Project-specific commands
## ─────────────────────────
##

check-map-credentials:
	@$(EXEC_PHP) php _dev_files/_check_map_credentials.php

get-maps-data: ## Download data for Esteren Maps
	@$(EXEC_PHP) php _dev_files/get_maps_data.php
	@$(EXEC_PHP) php _dev_files/cache_maps_data.php
.PHONY: get-maps-data

##
