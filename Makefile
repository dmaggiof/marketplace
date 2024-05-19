up:
	docker compose up -d --build

ssh:
	docker exec -it project_web bash

test:
	docker exec -it project_web vendor/bin/phpunit

xdebug:
	docker compose -f docker-compose.yml -f docker-compose.xdebug.yml up -d --build