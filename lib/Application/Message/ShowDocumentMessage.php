<?php

/*
 * This file is part of the org.icanboogie.www package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Message;

use ICanBoogie\MessageBus\Message;

class ShowDocumentMessage implements Message
{
	/**
	 * @var string
	 */
	public $version;

	/**
	 * @var string
	 */
	public $slug;

	/**
	 * @param string $version
	 * @param string $slug
	 */
	public function __construct($version, $slug)
	{
		$this->version = $version;
		$this->slug = $slug;
	}
}
