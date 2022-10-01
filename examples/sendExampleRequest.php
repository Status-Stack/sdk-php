<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Example send data
 */

use StatusStack\Client\ClientException;
use StatusStack\Dns\DnsResolverException;
use StatusStack\StatusStackException;
use StatusStack\StatusStackFactory;

try {

    StatusStackFactory::send(
        'https://{secret-key}@statusstack.app/api/v1/ticket/{id-project}',
        'sdk-php',
        'Request test',
        1
    );

} catch (ClientException|DnsResolverException|StatusStackException|\Nette\Utils\JsonException $exception) {
    echo $exception->getMessage();
}