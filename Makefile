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

clean:
	@rm -Rf vendor
