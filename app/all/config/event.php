<?php

namespace App;

use ICanBoogie;

$hooks = Hooks::class . '::';

return [

	ICanBoogie\Render\EngineCollection::class . '::alter' => $hooks . 'on_alter_engine_collection'

];
