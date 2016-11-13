<?php

/*
 * This file is part of the org.icanboogie.www package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use ICanBoogie\Render\EngineCollection;
use ICanBoogie\Render\MarkdownEngine;

class Hooks
{
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
}
