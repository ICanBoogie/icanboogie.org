## ICanBoogie is fun

The following example demonstrates how you can build you application rapidly, micro-framework style. 

```php
<?php

namespace ICanBoogie;

require 'vendor/autoload.php';

$app = boot();
$app->routes->get('/blog/articles', function() use ($app) {

	return $app->build_view()
		->with_content($app->models['articles']->online->ordered)
		->with_template('articles/index')
		->render();

});

$app();
```

## Easy to setup & run

```bash
$ composer create-project --prefer-dist -s dev icanboogie/app-hello hello
$ cd hello
$ ./icanboogie serve
```

## Interested?

- [Read the documentation](/docs)
- [Download additional packages](https://packagist.org/search/?q=icanboogie)
- [Check the project on GitHub](https://github.com/ICanBoogie/)
