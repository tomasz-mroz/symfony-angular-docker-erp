.PHONY: help build run stop down logs shell-php shell-node init

# Kolory do ładniejszego wyjścia w konsoli
COLOR_RESET = \033[0m
COLOR_INFO = \033[32m

help: ## Pokazuje dostępne komendy
	@echo "Dostępne komendy:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

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
	@echo "$(COLOR_INFO)Czyszczenie środowiska...$(COLOR_RESET)"
	docker compose down -v

logs: ## Wyświetla logi ze wszystkich kontenerów
	docker compose logs -f

shell-php: ## Otwiera terminal w kontenerze PHP (backend)
	docker compose exec php bash

shell-node: ## Otwiera terminal w kontenerze Node (frontend)
	docker compose exec frontend sh