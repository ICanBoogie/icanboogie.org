<?php

namespace App;

use ICanBoogie\Application;

return [

	Application::class . '::lazy_get_contents' => Domain\ContentCollection::class . '::for_app'

];
