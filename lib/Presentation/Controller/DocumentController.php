<?php

/*
 * This file is part of the icanboogie.org package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Presentation\Controller;

use App\Application\Message\Document\ShowDocument;
use ICanBoogie\Binding\MessageBus\ControllerBindings as MessageBusBindings;
use ICanBoogie\Routing\Controller;
use ICanBoogie\View\ControllerBindings as ViewBindings;

class DocumentController extends Controller
{
	use Controller\ActionTrait;
	use ViewBindings;
	use MessageBusBindings;

	/**
	 * @return \ICanBoogie\HTTP\RedirectResponse
	 */
	protected function action_get_index()
	{
		return $this->redirect('/docs/4.0');
	}

	/**
	 * @param string $version
	 *
	 * @return \ICanBoogie\HTTP\RedirectResponse
	 */
	protected function action_get_version_index($version)
	{
		return $this->redirect("/docs/$version/installation");
	}

	/**
	 * @param string $version
	 * @param string $slug
	 */
	protected function action_get_show($version, $slug)
	{
		$message = new ShowDocument($version, $slug);
		$vars = $this->dispatch_message($message);

		$this->response->cache_control = 'public';
		$this->response->expires = '+1 days';
		$this->view->assign($vars);
	}
}
