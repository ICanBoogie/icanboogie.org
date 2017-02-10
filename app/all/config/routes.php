<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use ICanBoogie\Routing\RouteDefinition as Route;
use App\Presentation\Controller;

return [

	'home' => [

		Route::PATTERN => '/',
		Route::CONTROLLER => Controller\PageController::class

	],

	'docs:index' => [

		Route::PATTERN => '/docs',
		Route::CONTROLLER => Controller\DocumentController::class,
		Route::ACTION => 'index'

	],

	'docs:version:index' => [

		Route::PATTERN => '/docs/<version:\d\.\d>',
		Route::CONTROLLER => Controller\DocumentController::class,
		Route::ACTION => 'version_index'

	],

	'docs:show' => [

		Route::PATTERN => '/docs/<version:\d\.\d>/:slug',
		Route::CONTROLLER => Controller\DocumentController::class,
		Route::ACTION => 'show'

	],

	'api:index' => [

		Route::PATTERN => '/api',
		Route::CONTROLLER => Controller\ApiController::class,
		Route::ACTION => 'index'

	]

];
