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

use App\Domain\Document\DocumentNotFound;
use ICanBoogie\AppAccessor;
use ICanBoogie\HTTP\NotFound;
use ICanBoogie\Routing\Controller;
use ICanBoogie\View\ControllerBindings as ViewBindings;

use const ICanBoogie\APP_ROOT;
use function ICanBoogie\format;

class DocumentController extends Controller
{
	use Controller\ActionTrait;
	use ViewBindings;
	use AppAccessor;

	const EDIT_LINK_TEMPLATE = 'https://github.com/ICanBoogie/docs/blob/{version}/{slug}.md';

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
		$html = $this->render_document($version, $slug);

		$this->response->cache_control = 'public';
		$this->response->expires = '+1 days';
		$this->view->content = $html;
		$this->view->assign([

			'sidebar' => $this->render_navigation($version),
			'edit_link' => $this->render_edit_link($version, $slug),
			'page_title' => $this->resolve_page_title($html)

		]);

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
		$filename = APP_ROOT . "_content/docs/$version/$name.md";

		if (!file_exists($filename))
		{
			throw new DocumentNotFound($version, $name);
		}

		$html = $this->render_template($filename);
		// adjust relative links
		$html = preg_replace('#"\./([^\.]+)\.md"#', '"./$1"', $html);

		return $html;
	}

	/**
	 * @param string $version
	 *
	 * @return string
	 */
	private function render_navigation($version)
	{
		$filename = APP_ROOT . "_content/docs/$version/README.md";
		$html = $this->render_template($filename);

		$html = preg_replace('#href="(?!/)#', 'href="/docs/' . $version . '/', $html);
		$html = preg_replace('#\.md#', '', $html);

		return $html;
	}

	/**
	 * @param string $filename
	 *
	 * @return string
	 */
	private function render_template($filename)
	{
		return $this->app->template_engines->render($filename, null, []);
	}

	/**
	 * @param string $version
	 * @param string $slug
	 *
	 * @return string
	 */
	private function render_edit_link($version, $slug)
	{
		return format(self::EDIT_LINK_TEMPLATE, compact('version', 'slug'));
	}

	/**
	 * @param string $html
	 *
	 * @return string|null
	 */
	private function resolve_page_title($html)
	{
		if (!preg_match('#<h1[^>]+>(.+)</h1>#', $html, $matches))
		{
			return null;
		}

		return strip_tags($matches[1]);
	}
}
