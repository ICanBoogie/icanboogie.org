<?php

namespace App;

$hooks = Hooks::class . '::';

return [

	'ICanBoogie\Routing\RouteDispatcher::dispatch' => $hooks . 'on_dispatch_routing_dispatcher',
	'ICanBoogie\View\View::alter'                  => $hooks . 'on_view_alter',

];
