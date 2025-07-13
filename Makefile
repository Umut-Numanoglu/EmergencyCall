# Emergency Call System - Makefile
# Common tasks for managing the Docker environment

.PHONY: help up down build recreate install logs shell db-shell test clean reset

# Default target
help: ## Show this help message
	@echo "Emergency Call System - Available Commands:"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# Docker Compose Commands
up: ## Start all containers in background
	docker-compose up -d

down: ## Stop and remove all containers
	docker-compose down

build: ## Build all containers
	docker-compose build

recreate: ## Recreate all containers (down, build, up)
	docker-compose down --rmi local -v
	docker-compose up -d --build
	docker-compose exec app composer install

restart: ## Restart all containers
	docker-compose restart

# Development Commands
install: ## Install Composer dependencies
	docker-compose exec app composer install

update: ## Update Composer dependencies
	docker-compose exec app composer update

logs: ## Show logs from all containers
	docker-compose logs -f

logs-app: ## Show logs from app container
	docker-compose logs -f app

logs-nginx: ## Show logs from nginx container
	docker-compose logs -f nginx

logs-db: ## Show logs from database container
	docker-compose logs -f db

# Shell Access
shell: ## Access shell in app container
	docker-compose exec app bash

db-shell: ## Access MySQL shell
	docker-compose exec db mysql -u emergency_user -p emergency_call

# Database Commands
db-reset: ## Reset database (drop and recreate)
	docker-compose exec db mysql -u emergency_user -p emergency_call -e "DROP DATABASE emergency_call; CREATE DATABASE emergency_call;"
	docker-compose exec db mysql -u emergency_user -p emergency_call < docker/mysql/init/01-init.sql

db-backup: ## Create database backup
	docker-compose exec db mysqldump -u emergency_user -p emergency_call > backup_$(shell date +%Y%m%d_%H%M%S).sql

db-restore: ## Restore database from backup (usage: make db-restore FILE=backup.sql)
	docker-compose exec -T db mysql -u emergency_user -p emergency_call < $(FILE)

# Testing and Validation
test: ## Run database connection test
	docker-compose exec app php web/test.php

test-app: ## Test application accessibility
	@echo "Testing application accessibility..."
	@curl -f http://localhost:8080 > /dev/null 2>&1 && echo "✅ Application is accessible" || echo "❌ Application is not accessible"

test-db: ## Test database connection
	@echo "Testing database connection..."
	@docker-compose exec app php -r "try { new PDO('mysql:host=db;dbname=emergency_call', 'emergency_user', 'emergency_password'); echo '✅ Database connection successful'; } catch (Exception \$e) { echo '❌ Database connection failed: ' . \$e->getMessage(); }"

# Maintenance Commands
clean: ## Clean up containers, images, and volumes
	docker-compose down --rmi all -v --remove-orphans
	docker system prune -f

reset: ## Complete reset (clean + recreate)
	$(MAKE) clean
	$(MAKE) recreate

# Permissions
fix-permissions: ## Fix file permissions
	docker-compose exec app chmod -R 777 runtime/
	docker-compose exec app chmod -R 777 web/assets/
	docker-compose exec app chmod -R 777 /var/www/.composer

# Status Commands
status: ## Show container status
	docker-compose ps

info: ## Show system information
	@echo "=== Emergency Call System Status ==="
	@echo "Application URL: http://localhost:8080"
	@echo "Database Host: localhost:3306"
	@echo "Database Name: emergency_call"
	@echo "Database User: emergency_user"
	@echo ""
	@echo "=== Container Status ==="
	@docker-compose ps
	@echo ""
	@echo "=== Default Users ==="
	@echo "Patient: patient1 / password"
	@echo "Reception: reception1 / password"
	@echo "Doctor: doctor1 / password"

# Development Workflow
dev-setup: ## Complete development setup
	$(MAKE) recreate
	$(MAKE) fix-permissions
	$(MAKE) install
	$(MAKE) test-app
	$(MAKE) info

# Quick Commands
quick-start: ## Quick start (up + install)
	$(MAKE) up
	$(MAKE) install
	$(MAKE) fix-permissions

quick-stop: ## Quick stop and cleanup
	$(MAKE) down
	$(MAKE) clean 