<?php

/*
 * This file is part of the icanboogie.org package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Api\Handler;

use App\Application\Message\Api\ListApi;

class ListApiHandler
{
	/**
	 * @var string
	 */
	private $root;

	/**
	 * @param string $root
	 */
	public function __construct($root)
	{
		$this->root = $root;
	}

	/**
	 * @param ListApi $message
	 *
	 * @return array
	 */
	public function __invoke(ListApi $message)
	{
		$content = $this->collect_packages();
		$content = $this->collect_versions($content);

		return compact('content');
	}

	/**
	 * @return array
	 */
	private function collect_packages()
	{
		$iterator = new \DirectoryIterator($this->root);
		$packages = [];

		foreach ($iterator as $file)
		{
			if (!$file->isDir() || $file->isDot())
			{
				continue;
			}

			$name = file_get_contents($file->getPathname() . '/.name');
			$package = $file->getBasename();

			$packages[$package] = [ 'name' => $name ];
		}

		ksort($packages);

		return $packages;
	}

	/**
	 * @param array $packages
	 *
	 * @return array
	 */
	private function collect_versions(array $packages)
	{
		foreach ($packages as $package => &$info)
		{
			$iterator = new \DirectoryIterator("$this->root/$package");
			$versions = &$info['versions'];

			foreach ($iterator as $file)
			{
				if (!$file->isDir() || $file->isDot())
				{
					continue;
				}

				$version = $file->getBasename();
				$versions[] = $version;
			}

			rsort($versions);
		}

		return $packages;
	}
}
