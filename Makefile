default: env prepare up key-generate populate
	@echo "--> Your environment is ready to use! Access http://laravel.test and enjoy it!"

.PHONY: env
env:
	@echo "--> Copying .env.example to .env file"
	@cp .env.example .env

.PHONY: prepare
prepare:
	@echo "--> Installing composer dependencies..."
	@sh ./bin/prepare.sh

.PHONY: up
up:
	@echo "--> Starting all docker containers..."
	@./vendor/bin/sail up --force-recreate -d

.PHONY: key-generate
key-generate:
	@echo "--> Generating new laravel key..."
	@./vendor/bin/sail art key:generate

.PHONE: populate
populate:
	@echo "--> Populating all table and data of project..."
	@./vendor/bin/sail art migrate:fresh --seed