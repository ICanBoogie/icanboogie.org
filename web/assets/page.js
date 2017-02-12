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

if (!String.prototype.startsWith) {
	String.prototype.startsWith = function(searchString, position){
		position = position || 0;
		return this.substr(position, searchString.length) === searchString;
	};
}

document.addEventListener("DOMContentLoaded", function() {

	"use strict"

	function forEach(nodeList, callback)
	{
		Array.prototype.forEach.call(nodeList, callback)
	}

	function adjustNavigationTrail()
	{
		var pathname = location.pathname + '/'
		var links = document.body.querySelectorAll('.nav-link')

		forEach(links, function (link) {

			var linkPathname = link.pathname.replace(/\/index.html$/, '')
			var method = pathname.startsWith(linkPathname) ? 'add' :  'remove'

			link.closest('li').classList[method]('trail')
		})
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

	function adjustSidebarOverflowing() {
		var sidebar = document.body.querySelector('.sidebar')

		if (!sidebar)
		{
			return
		}

		sidebar.classList.add('computing')

		var menu = sidebar.querySelector('ul')
		var max = 0

		forEach(menu.querySelectorAll('*'), function (el) {

			max = Math.max(max, el.scrollWidth)

		})

		var method = max > menu.clientWidth ? 'add' : 'remove'

		sidebar.classList[method]('overflowing')
		sidebar.classList.remove('computing')
	}

	function ready()
	{
		highlight()
		attachAnchors(assets['icon-anchor'])
		adjustOutgoingLinks()
		adjustSidebarActiveLink()
		adjustSidebarOverflowing()
		adjustNavigationTrail()
	}

	ready()

	/**
	 * Documentation
	 */

	!function() {

		var pushed = false

		function replace(selector, fragment)
		{
			var element = fragment.querySelector(selector)

			if (!element)
			{
				throw new Error("Unable to find element in fragment with: " + selector)
			}

			var target = document.body.querySelector(selector)

			if (!target)
			{
				throw new Error("Unable to target element with " + selector)
			}

			target.parentNode.insertBefore(element, target)
			target.parentNode.removeChild(target)
		}

		function changeDocument(href, then)
		{
			var xhr = new XMLHttpRequest()

			xhr.onreadystatechange = function(ev) {

				if (xhr.readyState != 4 || xhr.status != 200)
				{
					return;
				}

				var html = xhr.responseText
				var title = html.match(/<title>([\s\S]*?)<\/title>/i)[1];

				if (title)
				{
					document.head.querySelector('title').innerHTML = title
				}

				var match = html.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
				if (match) html = match[1];
				var temp = document.createElement('div')
				temp.innerHTML = html

				replace('.page-inner', temp)
				replace('.sidebar ul', temp)

				window.scrollTo(0, 0);

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
