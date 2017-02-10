<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie;

chdir(dirname(__DIR__));

/*
 * URL rewriting functionality for the built-in PHP web server.
 */
if (PHP_SAPI === 'cli-server')
{
	$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

	if (is_file(__DIR__ . $uri) || file_exists(__DIR__ . "$uri/index.html"))
	{
		return false;
	}

	unset($uri);
}

/*
 * Obtain the booted application and execute it.
 */
$app = require __DIR__ . '/../bootstrap.php';
$app();
