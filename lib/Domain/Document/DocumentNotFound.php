<?php

/*
 * This file is part of the org.icanboogie.www package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Document;

use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\HTTP\Status;

/**
 * @property-read string $document
 */
class DocumentNotFound extends NotFound
{
	use AccessorTrait;

	/**
	 * @var string
	 */
	private $version;

	/**
	 * @return string
	 */
	protected function get_version()
	{
		return $this->version;
	}

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @return string
	 */
	protected function get_name()
	{
		return $this->name;
	}

	/**
	 * @param string $version
	 * @param string $name
	 * @param \Exception|null $previous
	 */
	public function __construct($version, $name, \Exception $previous = null)
	{
		$this->version = $version;
		$this->name = $name;

		parent::__construct(self::DEFAULT_MESSAGE, Status::NOT_FOUND, $previous);
	}
}
