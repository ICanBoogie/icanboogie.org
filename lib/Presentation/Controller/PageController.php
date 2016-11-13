<?php

/*
 * This file is part of the org.icanboogie.www package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Presentation\Controller;

use ICanBoogie\AppAccessor;
use ICanBoogie\HTTP\Request;
use ICanBoogie\Routing\Controller;
use ICanBoogie\View\ControllerBindings as ViewBindings;

class PageController extends Controller
{
	use ViewBindings;
	use AppAccessor;

	/**
	 * @inheritdoc
	 */
	protected function action(Request $request)
	{
		$id = $this->route->id;
		$this->view->content = $this->app->contents["pages/$id"];
		$this->view['page_title'] = "PAGETITLE";
	}
}
