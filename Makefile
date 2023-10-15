-include .env

SUCCESS = "\\033[32m[+]\\033[0m "
ERROR = "\\033[31m[x]\\033[0m "
INFO = "\\033[34m[i]\\033[0m "
WARN = "\\033[33m[!]\\033[0m\\033[1m "
QUESTION = "\\033[36m[?]\\033[0m "

SEPERATOR = "\\033[90m...........................................\\033[0m"
NEW_LINE = "\\n"
BOLD=$(tput bold)
REGULAR=$(tput sgr0)

help:
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

#region Defines
define successLine
	echo "$(SUCCESS) $(1)"
endef

define errorLine
	echo "$(ERROR) $(1)"
endef

define infoLine
	echo "$(INFO) $(1)"
endef

define warnLine
	echo "$(WARN) $(1)"
endef

define seperatorLine
	@echo "$(NEW_LINE)$(SEPERATOR)$(NEW_LINE) "
endef

define check-requirements
	@which php > /dev/null || (echo "$(ERROR) PHP is not installed. Please install PHP first.")
	@which composer > /dev/null || (echo "$(ERROR) Composer is not installed. Please install Composer first.")
	@which docker > /dev/null || (echo "$(ERROR) Docker is not installed. Please install Docker first.")
	@which docker-compose > /dev/null || (echo "$(ERROR) Docker Compose is not installed. Please install Docker Compose first.")
	@which node > /dev/null || (echo "$(ERROR) Node is not installed. Please install Node first.")
	@which sed > /dev/null || (echo "$(ERROR) sed is not installed. Please install sed first.")
endef

define install-php
	@$(call infoLine, "Checking PHP...")
	@which php > /dev/null || (echo "$(ERROR) PHP is not installed. Installing PHP..." && brew install php && $(call successLine, "PHP installed"))
endef

define install-composer
	@$(call infoLine, "Checking Composer...")
	@which composer > /dev/null || (echo "$(ERROR) Composer is not installed. Installing Composer..." && brew install composer && $(call successLine, "Composer installed"))
endef

define install-docker
	@$(call infoLine, "Checking Docker...")
	@which docker > /dev/null || (echo "$(ERROR) Docker is not installed. Installing Docker..." && brew install docker && $(call successLine, "Docker installed"))
endef

define install-docker-compose
	@$(call infoLine, "Checking Docker Compose...")
	@which docker-compose > /dev/null || (echo "$(ERROR) Docker Compose is not installed. Installing Docker Compose..." && brew install docker-compose && $(call successLine, "Docker Compose installed"))
endef

define install-node
	@$(call infoLine, "Checking Node...")
	@which node > /dev/null || (echo "$(ERROR) Node is not installed. Installing Node..." && brew install node && $(call successLine, "Node installed"))
endef

define install-sed
	@$(call infoLine, "Checking sed...")
	@which sed > /dev/null || (echo "$(ERROR) sed is not installed. Installing sed..." && brew install sed && $(call successLine, "sed installed"))
endef

define create-env-files
	$(call seperatorLine)
	@$(call infoLine, "Checking .env file for Docker...")
	@if [ ! -f .env ]; then \
			read -p "[?]   Enter your desired NGINX port [default: 80]: " NGINX_PORT; \
			read -p "[?]   Enter your desired Redis port [default: 6379]: " REDIS_PORT; \
			read -p "[?]   Enter your desired MySQL port [default: 3306]: " MYSQL_PORT; \
			read -p "[?]   Enter your desired Database name: " MYSQL_DATABASE; \
			read -p "[?]   Enter your desired Database username: " MYSQL_USER; \
			read -p "[?]   Enter your desired Database password: " MYSQL_PASSWORD; \
			NGINX_PORT=$${NGINX_PORT:-80}; \
			REDIS_PORT=$${REDIS_PORT:-6379}; \
			MYSQL_PORT=$${MYSQL_PORT:-3306}; \
			cp .env.example .env; \
			touch ./infrastructure/mysql/init.sql; \
			echo "CREATE DATABASE IF NOT EXISTS $${MYSQL_DATABASE};\nGRANT ALL PRIVILEGES ON $${MYSQL_DATABASE}.* TO '$${MYSQL_USER}'@'localhost';" > ./infrastructure/mysql/init.sql; \
			sed -i '' "s/NGINX_PORT=/NGINX_PORT=$$NGINX_PORT/g" .env; \
			sed -i '' "s/REDIS_PORT=/REDIS_PORT=$$REDIS_PORT/g" .env; \
			sed -i '' "s/MYSQL_PORT=/MYSQL_PORT=$$MYSQL_PORT/g" .env; \
			sed -i '' "s/MYSQL_DATABASE=/MYSQL_DATABASE=$$MYSQL_DATABASE/g" .env; \
			sed -i '' "s/MYSQL_USER=/MYSQL_USER=$$MYSQL_USER/g" .env; \
			sed -i '' "s/MYSQL_PASSWORD=/MYSQL_PASSWORD=$$MYSQL_PASSWORD/g" .env; \
			$(call successLine, "Created /.env file"); \
		else \
			$(call infoLine, "/.env file already exists. No further action taken"); \
		fi
	$(call seperatorLine)

	@$(call infoLine, "Checking .env file for Laravel...")
	@if [ ! -f ./src/.env ]; then \
			set -a && source .env && set +a; \
			cp ./src/.env.example ./src/.env; \
			sed -i '' "s/DB_HOST=127.0.0.1/DB_HOST=mysql/g" ./src/.env; \
			sed -i '' "s/DB_DATABASE=laravel/DB_DATABASE=$${MYSQL_DATABASE}/g" ./src/.env; \
			sed -i '' "s/DB_USERNAME=root/DB_USERNAME=root/g" ./src/.env; \
			sed -i '' "s/DB_PASSWORD=/DB_PASSWORD=root/g" ./src/.env; \
			sed -i '' "s/REDIS_HOST=127.0.0.1/REDIS_HOST=redis/g" ./src/.env; \
			$(call successLine, "Created /src/.env file for Laravel"); \
		else \
			$(call infoLine, "/src/.env file already exists. No further action taken"); \
		fi;
endef

define install-composer-dependencies
	$(call seperatorLine)
	@$(call infoLine, "Installing Composer dependencies...")
	@cd ./src && composer install --no-interaction --no-progress --no-suggest --prefer-dist
	@cd ./src && php artisan key:generate
	@cd ..
endef

define build-containers
	$(call seperatorLine)
	@$(call check-containers-built) || ($(call infoLine, "Building Docker containers...") && make compose-up)
endef

compose-up: ## Ignore the check-containers-built check and force a docker-compose up
	@docker-compose build --no-cache && docker-compose up -d

define migrate-database
	@$(call seperatorLine);
	@$(call infoLine, "Migrating database...");
	@read -p "[?]   Do you want to migrate fresh? [y/N]: " MIGRATE_FRESH && if [ "$$MIGRATE_FRESH" = "y" ] || [ "$$MIGRATE_FRESH" = "Y" ]; then \
			docker-compose exec php-fpm php ./src/artisan migrate:fresh; \
			$(call successLine, "All tables dropped and remigrated."); \
		else \
			docker-compose exec php-fpm php ./src/artisan migrate; \
		fi;
	@$(call seperatorLine);
	@read -p "[?]   Do you want to seed the database? [y/N]: " SEED_DATABASE && if [ "$$SEED_DATABASE" = "y" ] || [ "$$SEED_DATABASE" = "Y" ]; then \
			docker-compose exec php-fpm php ./src/artisan db:seed; \
	else \
			$(call infoLine, "No action taken."); \
	fi;
endef
#endregion

define check-containers-built
	@docker-compose ps | grep -q "${SERVICE_NAME}"
endef

define ensure-application-built
	@$(call check-containers-built) || (echo "$(ERROR) Application is not built. Please run 'make build' first" && exit 1)
endef

#region Targets
build: ## Build the project
	@$(call infoLine, "Checking requirements...")
	$(call check-requirements)
	@make install-requirements
	$(call seperatorLine)
	@$(call infoLine, "Building case study...")
	@export NGINX_PORT=$${NGINX_PORT:-80};\
    		REDIS_PORT=$${REDIS_PORT:-6379};\
    		MYSQL_PORT=$${MYSQL_PORT:-3306};
	$(call create-env-files)
	$(call install-composer-dependencies)
	$(call build-containers)
	$(call ensure-application-built)
	@$(call warnLine, "Please run \'make migrate\' to migrate the database")
#    @docker-compose exec php-fpm php ./src/artisan l5-swagger:generate
#    @$(call successLine, "Done. You may reach the application\'s documentation at http://localhost:$(NGINX_PORT)/api/documentation")

define app-health-check
	@which npm > /dev/null || brew install npm
	@sudo npm install node-fetch -g
	$(call seperatorLine)
	@$(call infoLine, "Checking application health...")
	@node -e 'require("http")\
		.get("http://localhost:$(NGINX_PORT)/api/health",response => (response.statusCode === 200 \
			? console.log("\x1b[32m[+] \x1b[0m"," Application is healthy") \
			: console.error("\x1b[31m[x] \x1b[0m"," Something went wrong. Check your nginx logs or run [make build] command") && process.exit(2)))\
		.on("error", e => console.error("\x1b[31m[x] \x1b[0m"," Something went wrong. Check your nginx logs or run [make build] command") && process.exit(2));'
	@sleep 1;
endef

migrate:
	@$(call ensure-application-built)
	@$(call infoLine, "Preparing to the migration...")
	@$(call app-health-check)
	$(call migrate-database)
	@$(call successLine, "Done. You may reach the application at http://localhost:$(NGINX_PORT)")



install-requirements: ## Install all requirements
	@which brew > /dev/null || (echo "$(ERROR) brew is not installed. Please install brew first." && exit 1001)
	@$(call infoLine, "Installing requirements...")
	$(call install-php)
	$(call install-node)
	$(call install-composer)
	$(call install-docker)
	$(call install-docker-compose)
	$(call install-sed)
	$(call seperatorLine)
	@$(call successLine, "All requirements are met")
#endregion
