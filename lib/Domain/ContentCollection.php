<?php

/*
 * This file is part of the org.icanboogie.www package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain;

use ICanBoogie\Application;
use ICanBoogie\OffsetNotWritable;
use ICanBoogie\Render\EngineCollection;

class ContentCollection implements \ArrayAccess
{
	/**
	 * @var EngineCollection
	 */
	private $engines;

	/**
	 * @var string
	 */
	private $root;

	/**
	 * @param Application $app
	 *
	 * @return static
	 */
	static public function for_app(Application $app)
	{
		return new static($app->template_engines, $app->config['app-root'] . '/all/content/');
	}

	/**
	 * @param EngineCollection $engines
	 * @param string $root
	 */
	public function __construct(EngineCollection $engines, $root)
	{
		$this->engines = $engines;
		$this->root = $root;
	}

	/**
	 * @inheritdoc
	 */
	public function offsetExists($offset)
	{
		// TODO: Implement offsetExists() method.
	}

	/**
	 * @inheritdoc
	 */
	public function offsetGet($id)
	{
		$pathname = $this->root . $id . '.md';

		return $this->engines->render($pathname, null, []);
	}

	/**
	 * @inheritdoc
	 */
	public function offsetSet($offset, $value)
	{
		throw new OffsetNotWritable([ $offset, $this ]);
	}

	/**
	 * @inheritdoc
	 */
	public function offsetUnset($offset)
	{
		throw new OffsetNotWritable([ $offset, $this ]);
	}
}
