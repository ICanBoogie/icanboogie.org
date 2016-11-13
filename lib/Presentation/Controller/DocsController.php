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

use App\Application\Exception\DocumentNotFound;
use ICanBoogie\AppAccessor;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\Routing\Controller;
use ICanBoogie\View\ControllerBindings as ViewBindings;

use const ICanBoogie\APP_DIR;

class DocsController extends Controller
{
	use Controller\ActionTrait;
	use ViewBindings;
	use AppAccessor;

	/**
	 * @return \ICanBoogie\HTTP\RedirectResponse
	 */
	protected function action_get_index()
	{
		return $this->redirect('/docs/4.0');
	}

	/**
	 * @param string $version
	 */
	protected function action_get_version_index($version)
	{
		$this->action_get_show($version, 'installation');
	}

	/**
	 * @param string $version
	 * @param string $slug
	 */
	protected function action_get_show($version, $slug)
	{
		$this->response->cache_control = 'public';
		$this->response->expires = '+1 days';
		$this->view->content = $this->render_document($version, $slug);
		$this->view['sidebar'] = $this->render_navigation($version);
		$this->view->template = 'doc';
	}

	/**
	 * @param string $version
	 * @param string $name
	 *
	 * @return string
	 *
	 * @throws NotFound if the document doesn't exists
	 */
	private function render_document($version, $name)
	{
		$filename = APP_DIR . "/all/content/docs/$version/$name.md";

		if (!file_exists($filename))
		{
			throw new DocumentNotFound($version, $name);
		}

		return $this->render_template($filename);
	}

	/**
	 * @param string $version
	 *
	 * @return string
	 */
	private function render_navigation($version)
	{
		$filename = APP_DIR . "/all/content/docs/$version/README.md";
		$html = $this->render_template($filename);

		$html = preg_replace('#href="(?!/)#', 'href="/docs/' . $version . '/', $html);
		$html = preg_replace('#\.md#', '', $html);

		return $html;
	}

	/**
	 * @param string $filename
	 */
	private function render_template($filename)
	{
		return $this->app->template_engines->render($filename, null, []);
	}
}
