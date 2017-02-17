<?php

/*
 * This file is part of the icanboogie.org package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use ICanBoogie\Routing\RouteDispatcher;
use ICanBoogie\View\View;

class Hooks
{
	/**
	 * @param RouteDispatcher\DispatchEvent $event
	 * @param RouteDispatcher $target
	 */
	static public function on_dispatch_routing_dispatcher(RouteDispatcher\DispatchEvent $event, RouteDispatcher $target)
	{
		$response = $event->response;

		if (!$response || !$response->body || $response->content_type->type != 'text/html')
		{
			return;
		}

		$response->body = self::render_stats() . $response->body;
	}

	/**
	 * Add the `page_id` variable to the view, created from the route's id.
	 *
	 * @param View\AlterEvent $event
	 * @param View $target
	 */
	static public function on_view_alter(View\AlterEvent $event, View $target)
	{
		try
		{
			$target['page_id'] = \ICanBoogie\normalize($target->controller->route->id);
		}
		catch (\Exception $e)
		{
			//
		}
	}

	/**
	 * @return string
	 */
	static private function render_stats()
	{
		$boot_time = round(($_SERVER['ICANBOOGIE_READY_TIME_FLOAT'] - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 3);
		$total_time = round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 3);

		return "<!-- booted in: $boot_time ms, completed in $total_time ms -->";
	}
}
