<?php

/*
 * This file is part of the org.icanboogie.www package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Message;

use ICanBoogie\Binding\MessageBus\MessageBusConfig;
use function ICanBoogie\Service\ref;

return [

	MessageBusConfig::HANDLERS => [

		ShowDocumentMessage::class => ref('handler.document.show')

	]

];
