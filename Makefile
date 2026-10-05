.PHONY: help build run stop down logs shell-php shell-node init diff migrate migrate-status migrate-rollback schema-validate db-shell db-tables create-user fixtures

# Kolory do ładniejszego wyjścia w konsoli
COLOR_RESET = \033[0m
COLOR_INFO = \033[32m
COLOR_WARN = \033[33m
COLOR_ERR = \033[31m

help: ## Pokazuje dostępne komendy
	@echo "Dostępne komendy:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Buduje obrazy Dockera
	@echo "$(COLOR_INFO)Budowanie obrazów...$(COLOR_RESET)"
	docker compose build

run: ## Uruchamia kontenery w tle (odpowiednik docker compose up -d)
	@echo "$(COLOR_INFO)Uruchamianie aplikacji...$(COLOR_RESET)"
	docker compose up -d

stop: ## Zatrzymuje działające kontenery
	@echo "$(COLOR_INFO)Zatrzymywanie...$(COLOR_RESET)"
	docker compose stop

down: ## Usuwa kontenery, sieci i wolumeny (uwaga na dane!)
	@echo "$(COLOR_WARN)Czyszczenie środowiska...$(COLOR_RESET)"
	docker compose down -v

logs: ## Wyświetla logi ze wszystkich kontenerów
	docker compose logs -f

shell-php: ## Otwiera terminal w kontenerze PHP (backend)
	docker compose exec php bash

shell-node: ## Otwiera terminal w kontenerze Node (frontend)
	docker compose exec frontend sh

# ============================================================
# Doctrine / Migracje
# ============================================================

diff: ## Generuje migrację na podstawie różnic encja ↔ baza
	@echo "$(COLOR_INFO)Generowanie migracji (doctrine:migrations:diff)...$(COLOR_RESET)"
	docker compose exec php php bin/console doctrine:migrations:diff

migrate: ## Wykonuje wszystkie oczekujące migracje
	@echo "$(COLOR_INFO)Wykonywanie migracji (doctrine:migrations:migrate)...$(COLOR_RESET)"
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

migrate-status: ## Pokazuje status migracji
	@echo "$(COLOR_INFO)Status migracji...$(COLOR_RESET)"
	docker compose exec php php bin/console doctrine:migrations:status

migrate-rollback: ## Cofa ostatnią migrację
	@echo "$(COLOR_WARN)Cofanie ostatniej migracji...$(COLOR_RESET)"
	docker compose exec php php bin/console doctrine:migrations:migrate prev --no-interaction

migrate-dry-run: ## Pokazuje SQL migracji bez jej wykonania
	@echo "$(COLOR_INFO)Dry-run migracji...$(COLOR_RESET)"
	docker compose exec php php bin/console doctrine:migrations:migrate --dry-run

schema-validate: ## Sprawdza, czy encje są zgodne z bazą
	@echo "$(COLOR_INFO)Walidacja schematu...$(COLOR_RESET)"
	docker compose exec php php bin/console doctrine:schema:validate

# ============================================================
# Baza danych
# ============================================================

db-shell: ## Otwiera psql w kontenerze db
	docker compose exec db psql -U symfony -d app_db

db-tables: ## Wyświetla listę tabel
	docker compose exec db psql -U symfony -d app_db -c "\dt"

db-users: ## Wyświetla użytkowników
	docker compose exec db psql -U symfony -d app_db -c "SELECT id, email, roles FROM \"user\";"