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

use App\Application\Message\Api\ListApi;
use ICanBoogie\Binding\MessageBus\ControllerBindings as MessageBusBindings;
use ICanBoogie\Binding\PrototypedBindings as ApplicationBindings;
use ICanBoogie\Routing\Controller;
use ICanBoogie\View\ControllerBindings as ViewBindings;

class ApiController extends Controller
{
	use Controller\ActionTrait;
	use ViewBindings;
	use MessageBusBindings;
	use ApplicationBindings;

	protected function action_index()
	{
		$vars = $this->dispatch_message(new ListApi());

		$this->response->expires = '+1 days';
		$this->response->last_modified = filectime('web/api');
		$this->response->etag = sha1(serialize($vars));

		$this->view->assign($vars);
	}
}
