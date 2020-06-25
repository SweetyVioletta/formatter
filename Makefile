install:
	docker-compose up
	docker container run --rm -v $(CURDIR):/app/ -w="/app" php:7.4.7 php /app/app.php
