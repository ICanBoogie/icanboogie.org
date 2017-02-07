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

use ICanBoogie\Render\EngineCollection;
use ICanBoogie\Render\MarkdownEngine;
use ICanBoogie\Routing\RouteDispatcher;

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
	 * Add Markdown to the engine collection.
	 *
	 * @param EngineCollection\AlterEvent $event
	 * @param EngineCollection $target
	 */
	static public function on_alter_engine_collection(EngineCollection\AlterEvent $event, EngineCollection $target)
	{
		$target['.md'] = MarkdownEngine::class;
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
