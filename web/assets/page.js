/*
 * Element.prototype
 */
!function (prototype) {

	/**
	 * Adds a delegated event listener.
	 *
	 * @param {string} selector
	 * @param {string} type
	 * @param {function} listener
	 * @param {boolean} [useCapture]
	 */
	prototype.addDelegatedEventListener = function(selector, type, listener, useCapture) {

		this.addEventListener(type, function(ev) {

			var target = ev.target
			var delegationTarget = target.closest(selector)

			if (!delegationTarget)
			{
				return null
			}

			listener(ev, delegationTarget, target)

		}, useCapture)

	}

} (Element.prototype)

document.addEventListener("DOMContentLoaded", function() {

	"use strict"

	function forEach(nodeList, callback)
	{
		Array.prototype.forEach.call(nodeList, callback)
	}

	function indexAssets()
	{
		var assets = {}

		forEach(document.querySelectorAll('[data-asset]'), function (asset) {

			assets[asset.getAttribute('data-asset')] = asset

		})

		return assets
	}

	var assets = indexAssets()

	function attachAnchors(icon)
	{
		forEach(document.querySelectorAll('a.anchor'), function (anchor) {

			anchor.appendChild(icon.cloneNode(true))

		})
	}

	function highlight()
	{
		forEach(document.querySelectorAll('pre code:not(.hljs)'), function(code) {

			hljs.highlightBlock(code)

		})
	}

	function adjustOutgoingLinks()
	{
		var links = document.body.querySelectorAll('[href^="http"]')

		for (var i = 0, j = links.length ; i < j ; i++)
		{
			links[i].target = '_blank'
		}
	}

	function adjustSidebarActiveLink()
	{
		var sidebar = document.querySelector('.sidebar')

		if (!sidebar) return

		var active = sidebar.querySelector('.active')

		if (active)
		{
			active.className = ''
		}

		var pathname = window.location.pathname

		try
		{
			forEach(sidebar.querySelectorAll('[href]'), function (anchor) {

				if (pathname != anchor.getAttribute('href'))
				{
					return
				}

				anchor.closest('li').className = 'active'

				throw "done"

			})
		}
		catch (e) {}
	}

	function ready()
	{
		highlight()
		attachAnchors(assets['icon-anchor'])
		adjustOutgoingLinks()
		adjustSidebarActiveLink()
	}

	ready()

	/**
	 * Documentation
	 */

	!function() {

		var pushed = false

		function changeDocument(href, then)
		{
			var xhr = new XMLHttpRequest()

			xhr.onreadystatechange = function(ev) {

				if (xhr.readyState != 4 || xhr.status != 200)
				{
					return;
				}

				var html = xhr.responseText
				var match = html.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
				if (match) html = match[1];
				var temp = document.createElement('div')
				temp.innerHTML = html

				var inner = temp.querySelector('.page-inner')

				if (!inner) {
					return
				}

				var replace = document.querySelector('.page-inner')

				window.scrollTo(0, 0);
				replace.parentNode.appendChild(inner)
				replace.parentNode.removeChild(replace)

				if (then)
				{
					then(ev)
				}

				ready()
			}

			xhr.open("GET", href);
			xhr.send(null);

		}

		window.onpopstate = function (ev) {

			if (!ev.state || !ev.state.href)
			{
				if (pushed)
				{
					changeDocument(window.location)
					pushed = false
				}

				return
			}

			changeDocument(ev.state.href)

		}

		document.body.addDelegatedEventListener('.sidebar a', 'click', function (ev, anchor) {

			ev.preventDefault()

			var href = anchor.href

			changeDocument(href, function () {

				pushed = true
				history.pushState({ href: href }, '', href)

			})

		})

	} ()

})
