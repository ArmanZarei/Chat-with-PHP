help:
	@echo 'up: this will run the project'
	@echo 'down: this will stop the project'

up:
	docker-compose up --build -d

down:
	docker-compose down
