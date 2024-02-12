
DOCKER_PREFIX = docker-compose exec web-server

PHP_CMD = docker-compose exec web-server php
COMPOSER_CMD = docker-compose exec web-server composer
NPM_CMD = $(DOCKER_PREFIX) npm
#COMPOSER_CMD = compose
#PHP_CMD = php
.DEFAULT_GOAL:=help
rebuild:
	-$(COMPOSER_CMD) install


	-$(NPM_CMD) install
	-$(NPM_CMD) run dev

	@ echo "Esborrant imatges..."
	-$(DOCKER_PREFIX) rm -r public/equip3/img/vehicles/*.jpg

	@ echo "Creant directori images"
	-$(DOCKER_PREFIX) mkdir public/equip3/img/vehicles -p
	-$(DOCKER_PREFIX) chmod 775 public/equip3/img/vehicles
	-umask 0002 public/equip3
	-$(DOCKER_PREFIX) chgrp www-data -R public/equip3/*

	@ echo "Creació del la carpeta mèdia amb els permisos corresponents"
	-$(DOCKER_PREFIX) mkdir public/media -p
	-$(DOCKER_PREFIX) chmod 777 public/media
	-umask 0002 public/media
	-$(DOCKER_PREFIX) chgrp www-data public/media

	@ echo "Esborrant la base de dades..."
	-$(PHP_CMD) bin/console doctrine:database:drop -n --force

	@ echo "Creant-la de nous..."
	$(PHP_CMD) bin/console doctrine:database:create -n

	@ echo "Creant l'estructura..."
	$(PHP_CMD) bin/console doctrine:migrations:migrate -n


	@ echo "Carregant les dades..."
	$(PHP_CMD) bin/console doctrine:fixtures:load -n

	@ echo "Instal·lant assets FOSCKEditorBundle.."
	$(PHP_CMD) bin/console assets:install

rebuild-test:
	-$(COMPOSER_CMD) install

	-$(NPM_CMD) install
	-$(NPM_CMD) run dev

	@ echo "Esborrant la base de dades..."
	-$(PHP_CMD) bin/console doctrine:database:drop -n --force --env=test

	@ echo "Creant-la de nous..."
	$(PHP_CMD) bin/console doctrine:database:create -n --env=test

	@ echo "Creant l'estructura..."
	$(PHP_CMD) bin/console doctrine:migrations:migrate -n --env=test


	@ echo "Carregant les dades..."
	$(PHP_CMD) bin/console doctrine:fixtures:load -n --env=test

help:
	@ echo "Utilitza 'make rebuild' per a regenerar les dades o 'make rebuild-test'"
