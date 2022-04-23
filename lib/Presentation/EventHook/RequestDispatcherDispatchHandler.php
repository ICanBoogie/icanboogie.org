<?php

/*
 * This file is part of the icanboogie.org package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Presentation\EventHook;

use ICanBoogie\HTTP\RequestDispatcher;
use ICanBoogie\HTTP\Response;

class RequestDispatcherDispatchHandler
{
	/**
	 * @param RequestDispatcher\DispatchEvent $event
	 * @param RequestDispatcher $target
	 */
	public function __invoke(RequestDispatcher\DispatchEvent $event, RequestDispatcher $target)
	{
		$response = $event->response;

		if ($response->expires->is_empty || !is_string($response->body))
		{
			return;
		}

		$this->ensure_response_has_etag($response);

		\ICanBoogie\app()->vars['page-' . $response->etag] = $response->body;
	}

	/**
	 * @param Response $response
	 */
	public function ensure_response_has_etag(Response $response)
	{
		if ($response->etag)
		{
			return;
		}

		$response->etag = sha1($response->body);
	}
}
