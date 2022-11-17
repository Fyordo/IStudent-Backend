#!make

up:
	docker-compose up -d
	docker-compose up -d
	docker exec -it istudent-nginx bash -c "chmod -R guo+w /var/www/storage"

down:
	docker-compose down

console:
	docker exec -it istudent-php bash
