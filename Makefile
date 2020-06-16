install: ## Install dependencies
	@echo "Installing dependencies"
	@composer update
	@docker-compose up