<?php

namespace App;

$hooks = Hooks::class . '::';

return [

	'ICanBoogie\Routing\RouteDispatcher::dispatch' => $hooks . 'on_dispatch_routing_dispatcher',
	'ICanBoogie\Render\EngineCollection::alter' => $hooks . 'on_alter_engine_collection',

];
