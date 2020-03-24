
PRECOMMIT_FILES := $(shell git --no-pager diff --name-only --diff-filter=ACM HEAD -- | grep .php$$)
DOCKER_COMPOSE := docker-compose -f docker/docker-compose.yml
CS_FIXER := vendor/friendsofphp/php-cs-fixer/php-cs-fixer
PHP_UNIT := ./vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox
INIT_FOLDERS = sh ./scripts/folders.sh
.SILENT: cs-fix-all cs-check cs-fix

uid:
    UID := $(shell id -u)
    export UID

#Docker-compose

run:
	$(INIT_FOLDERS) \
	&& $(DOCKER_COMPOSE) up --build -d
down:
	$(DOCKER_COMPOSE) down
kill:
	$(DOCKER_COMPOSE) kill
recreate:
	$(DOCKER_COMPOSE) create --force-recreate --build

#PHP - Composer

composer-install:
	$(DOCKER_COMPOSE) exec -u $(UID):$(UID) php composer install
composer-update:
	$(DOCKER_COMPOSE) exec -u $(UID):$(UID) php composer update
composer-require:
ifdef COMPOSERLIBRARY
	$(DOCKER_COMPOSE) exec -u $(UID):$(UID) php composer require $(COMPOSERLIBRARY)
endif

#CS-Fixer

cs-fix-all:
	$(DOCKER_COMPOSE) exec php $(CS_FIXER) fix --config=scripts/.php_cs.dist --using-cache=no
cs-check:
	$(DOCKER_COMPOSE) exec php $(CS_FIXER) fix --config=scripts/.php_cs.dist --dry-run --using-cache=no
cs-fix:
	if [ $(words ${PRECOMMIT_FILES}) -gt 0 ]; then \
			$(DOCKER_COMPOSE) exec php $(CS_FIXER) fix --config=scripts/.php_cs.dist --using-cache=no $(PRECOMMIT_FILES);\
	fi

#PHPUnit:
php-unit-all:
	$(DOCKER_COMPOSE) exec php $(PHP_UNIT) --do-not-cache-result

#PHPStan:
php-stan: run
	$(DOCKER_COMPOSE) exec php vendor/bin/phpstan analyse src tests --level 4

shell-scripts:
	$(DOCKER_COMPOSE) exec php /bin/sh; exit 0;

#CommandCalls
create-player:
	$(DOCKER_COMPOSE) exec php bin/console app:create-player --no-debug; exit 0;
delete-player:
	$(DOCKER_COMPOSE) exec php bin/console app:delete-player --no-debug; exit 0;
list-events:
	$(DOCKER_COMPOSE) exec php bin/console app:list-events --no-debug; exit 0;
list-players:
	$(DOCKER_COMPOSE) exec php bin/console app:list-players --no-debug; exit 0;
optimize-tactic:
	$(DOCKER_COMPOSE) exec php bin/console app:optimize-tactic --no-debug; exit 0;