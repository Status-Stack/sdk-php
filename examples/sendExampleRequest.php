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
        'http://nginx@exampleKey1234',
        'sdk',
        'Example data',
        0
    );

} catch (ClientException|DnsResolverException|StatusStackException|\Nette\Utils\JsonException $exception) {
    echo $exception->getMessage();
}