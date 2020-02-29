#up:
#    docker-compose up -d
#
#build:
#    docker-compose up -d --build
#
#composer-install:
#    docker-compose run --rm pm-php-cli composer install
#
#composer-install-without-dev:
#    docker-compose run --rm pm-php-cli composer install --no-dev
#
#composer-install-encore:
#    docker-compose run --rm pm-php-cli composer require encore
#
#composer-install-assets-dependencies:
#    docker-compose run --rm pm-node yarn add -s bootstrap jquery popper.js
#
#composer-install-dev-assets-dependencies:
#    docker-compose run --rm pm-node yarn add -s --dev sass-loader node-sass

# —— Inspired by ———————————————————————————————————————————————————————————————
# http://fabien.potencier.org/symfony4-best-practices.html
# https://speakerdeck.com/mykiwi/outils-pour-ameliorer-la-vie-des-developpeurs-symfony?slide=47
# https://blog.theodo.fr/2018/05/why-you-need-a-makefile-on-your-project/

# Setup ————————————————————————————————————————————————————————————————————————
SHELL          = bash
PROJECT        = strangebuzz
EXEC_FPM       = docker-compose run --rm pm-php-fpm
EXEC_PHP       = $(EXEC_FPM) php
EXEC_NPM       = docker-compose run --rm pm-node-cli npm
REDIS          = redis-cli
GIT            = git
GIT_AUTHOR     = COil
SYMFONY        = $(EXEC_PHP) bin/console
SYMFONY_BIN    = $(EXEC_FPM) ./symfony
# COMPOSER      = $(EXEC_PHP) composer.phar
COMPOSER       = $(EXEC_FPM) composer
NPM			   = npm
DOCKER         = docker-compose

.DEFAULT_GOAL  = help
#.PHONY       = # Not needed for now

## —— 🐝 The Strangebuzz Symfony Makefile 🐝 ———————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

wait: ## Sleep 5 seconds
	sleep 5

## —— Composer 🧙‍♂️ ————————————————————————————————————————————————————————————
install: app/composer.lock ## Install vendors according to the current composer.lock file
	$(COMPOSER) install

update: app/composer.json ## Update vendors according to the composer.json file
	$(COMPOSER) update

## —— Node 🧙‍♂️ ————————————————————————————————————————————————————————————
npm-install: app/yarn.lock ## Install vendors according to the current yarn.lock file
	$(EXEC_NPM) install

npm-update: app/package.json ## Update vendors according to the package.json file
	$(EXEC_NPM)  update

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands
	$(SYMFONY)

cc: ## Clear the cache. DID YOU CLEAR YOUR CACHE????
	$(SYMFONY) c:c

warmup: ## Warmump the cache
	$(SYMFONY) cache:warmup

fix-perms: ## Fix permissions of all var files
	chmod -R 777 app/var/*

assets: purge ## Install the assets with symlinks in the public folder
	$(SYMFONY) assets:install public/ --symlink --relative

purge: ## Purge cache and logs
	$(EXEC_FPM) rm -rf var/cache/* var/logs/*

## —— Symfony binary 💻 ————————————————————————————————————————————————————————
bin-install: ## Download and install the binary in the project (file is ignored)
	$(EXEC_FPM) curl -sS https://get.symfony.com/cli/installer | bash
	mv ~/.symfony/bin/symfony .

cert-install: symfony ## Install the local HTTPS certificates
	$(SYMFONY_BIN) server:ca:install

serve: symfony ## Serve the application with HTTPS support
	$(SYMFONY_BIN) serve --daemon

unserve: symfony ## Stop the web server
	$(SYMFONY_BIN) server:stop

# L70+12 => templates/blog/posts/_64.html.twig

## —— elasticsearch 🔎 —————————————————————————————————————————————————————————
populate: ## Reset and populate the elasticsearch index
	$(SYMFONY) fos:elastica:reset
	$(SYMFONY) fos:elastica:populate --index=app
	$(SYMFONY) strangebuzz:populate

# L86+4 => templates/blog/posts/_51.html.twig

list-index: ## List all indexes on the cluster
	$(EXEC_FPM) curl http://localhost:9209/_cat/indices?v

delete-index: ## Delete a given index (replace index by the index name to delete)
	$(EXEC_FPM) curl -X DELETE "localhost:9209/index?pretty"

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
up: docker-compose.yaml ## Start the docker hub (MySQL,redis,adminer,elasticsearch,head,Kibana)
	$(DOCKER) -f docker-compose.yaml up -d

down: docker-compose.yaml ## Stop the docker hub
	$(DOCKER) down --remove-orphans

rebuild: down up ## Stop and start the docker hub

## —— Project 🐝 ———————————————————————————————————————————————————————————————
deploy-development: install

run: up wait reload serve ## Start docker, load fixtures, populate the Elasticsearch index and start the web server

reload: load-fixtures populate ## Reload fixtures and repopulate the Elasticserch index

abort: down unserve ## Stop docker and the Symfony binary server

cc-redis: ## Flush all Redis cache
	$(REDIS) flushall

commands: ## Display all commands in the project namespace
	$(SYMFONY) list $(PROJECT)

load-fixtures: ## Build the db, control the schema validity, load fixtures and check the migration status
	$(SYMFONY) doctrine:cache:clear-metadata
	$(SYMFONY) doctrine:database:create --if-not-exists
	$(SYMFONY) doctrine:schema:drop --force
	$(SYMFONY) doctrine:schema:create
	## $(SYMFONY) doctrine:schema:validate
	$(SYMFONY) doctrine:fixtures:load -n
	## $(SYMFONY) doctrine:schema:validate

init-snippet: ## Initialize a new snippet
	$(SYMFONY) $(PROJECT):init-snippet

## —— Tests ✅ —————————————————————————————————————————————————————————————————
test: phpunit.xml ## Launch main functionnal and unit tests
	./bin/phpunit --testsuite=main --stop-on-failure

test-external: phpunit.xml ## Launch tests implying external resources (api, services...)
	./bin/phpunit --testsuite=external --stop-on-failure

test-all: phpunit.xml ## Launch all tests
	./bin/phpunit --stop-on-failure

## —— Coding standards ✨ ——————————————————————————————————————————————————————
cs: codesniffer stan ## Launch check style and static analysis

codesniffer: ## Run php_codesniffer only
	./vendor/squizlabs/php_codesniffer/bin/phpcs --standard=phpcs.xml -n -p src/

stan: ## Run PHPStan only
	./vendor/bin/phpstan analyse -l max --memory-limit 1G -c phpstan.neon src/

psalm: ## Run psalm only
	./vendor/bin/psalm --show-info=false

init-psalm: ## Init a new psalm config file for a given level, it must be decremented to have stricter rules
	rm ./psalm.xml
	./vendor/bin/psalm --init src/ 3

cs-fix: ## Run php-cs-fixer and fix the code.
	./vendor/bin/php-cs-fixer fix src/

## —— Deploy & Prod 🚀 —————————————————————————————————————————————————————————
deploy: ## Full no-downtime deployment with EasyDeploy
	$(SYMFONY) deploy -v

env-check: ## Check the main ENV variables of the project
	printenv | grep -i app_

le-renew: ## Renew Let's encrypt HTTPS cerificates
	/opt/letsencrypt/letsencrypt-auto

## —— Stats 📜 —————————————————————————————————————————————————————————————————
stats: ## Commits by hour for the main author of this project
	$(GIT) log --author="$(GIT_AUTHOR)" --date=iso | perl -nalE 'if (/^Date:\s+[\d-]{10}\s(\d{2})/) { say $$1+0 }' | sort | uniq -c|perl -MList::Util=max -nalE '$$h{$$F[1]} = $$F[0]; }{ $$m = max values %h; foreach (0..23) { $$h{$$_} = 0 if not exists $$h{$$_} } foreach (sort {$$a <=> $$b } keys %h) { say sprintf "%02d - %4d %s", $$_, $$h{$$_}, "*"x ($$h{$$_} / $$m * 50); }'