<?php

use Cornford\Pokenotifier\Models\Position;
use Cornford\Pokenotifier\Notifier;

require __DIR__ . '/vendor/autoload.php';

$notifier = new Notifier();

$configuration = $notifier->getApplicationConfiguration();
$position = new Position(
    (isset($_REQUEST['lat']) ? $_REQUEST['lat'] : $configuration['default-latitude']),
    (isset($_REQUEST['lon']) ? $_REQUEST['lon'] : $configuration['default-longitude'])
);

switch ((isset($_REQUEST['type']) ? $_REQUEST['type'] : 'scan')) {
    case 'webhook':
        $notifier->processWebhookRequest(
            (array) json_decode(file_get_contents('php://input')),
            $position
        );
        break;
    case 'scan':
    default:
        $notifier->processScanRequest(
            $position
        );
}

echo json_encode(['response' => 'ok']);