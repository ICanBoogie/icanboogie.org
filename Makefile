ICANBOOGIE_INSTANCE=dev
SERVER_PORT=8020

vendor:
	@composer install

update: vendor
	@composer update

autoload: vendor
	@composer dump-autoload

optimize: vendor
	@composer dump-autoload -o
	@icanboogie optimize

unoptimize:
	@composer dump-autoload
	@rm -f vendor/icanboogie-combined.php
	@icanboogie clear cache

server:
	@rm -rf repository/cache/*
	@rm -rf repository/var/*
	@rm -f repository/db.sqlite
	@echo "Open http://localhost:$(SERVER_PORT) when ready."
	@docker-compose up
