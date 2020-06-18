install:
	@git pull
	@docker-compose up
	@docker container run --rm -v $(pwd):/app/ -w="/app" php:7.4.7 php /app/app.php
