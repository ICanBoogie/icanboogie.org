<?php

/*
 * This file is part of the icanboogie.org package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Handler;

use App\Application\Message\ShowDocumentMessage;
use App\Domain\Document\DocumentNotFound;
use ICanBoogie\Render\EngineCollection;
use const ICanBoogie\APP_ROOT;
use function ICanBoogie\format;

class ShowDocumentHandler
{
	/**
	 * @var EngineCollection
	 */
	private $template_engines;

	/**
	 * @var string
	 */
	private $edit_link_pattern;

	/**
	 * @param EngineCollection $template_engines
	 * @param string $edit_link_pattern
	 */
	public function __construct(EngineCollection $template_engines, $edit_link_pattern)
	{
		$this->template_engines = $template_engines;
		$this->edit_link_pattern = $edit_link_pattern;
	}

	/**
	 * @param ShowDocumentMessage $message
	 *
	 * @return array
	 */
	public function __invoke(ShowDocumentMessage $message)
	{
		$version = $message->version;
		$slug = $message->slug;
		$content = $this->render_document($version, $slug);

		return [

			'content' => $content,
			'sidebar' => $this->render_navigation($version),
			'edit_link' => $this->render_edit_link($version, $slug),
			'page_title' => $this->resolve_page_title($content)

		];
	}

	/**
	 * @param string $version
	 * @param string $name
	 *
	 * @return string
	 *
	 * @throws DocumentNotFound if the document doesn't exists
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
		return $this->template_engines->render($filename, null, []);
	}

	/**
	 * @param string $version
	 * @param string $slug
	 *
	 * @return string
	 */
	private function render_edit_link($version, $slug)
	{
		return format($this->edit_link_pattern, compact('version', 'slug'));
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
