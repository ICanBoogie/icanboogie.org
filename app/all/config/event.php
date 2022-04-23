<?php

namespace App;

$hooks = Hooks::class . '::';

return [

	'ICanBoogie\Render\EngineCollection::alter'    => $hooks . 'on_alter_engine_collection',
	'ICanBoogie\Routing\RouteDispatcher::dispatch' => $hooks . 'on_dispatch_routing_dispatcher',
	'ICanBoogie\View\View::alter'                  => $hooks . 'on_view_alter',

];
