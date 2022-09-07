<?php declare(strict_types=1);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Example send data
 */

use Nette\Utils\Json;
use StatusStack\Client\ClientException;
use StatusStack\Dns\DnsResolverException;
use StatusStack\StatusStackException;
use StatusStack\StatusStackFactory;

try {

    $dataBag = [
        'GET' => $_GET,
        'POST' => $_POST,
        'SESSION' => $_SESSION,
        'COOKIES' => $_COOKIE,
        'SERVER' => $_SERVER
    ];

    StatusStackFactory::send(
        'http://nginx@exampleKey1234',
        'sdk',
        Json::encode($dataBag),
        0
    );

} catch (ClientException|DnsResolverException|StatusStackException|\Nette\Utils\JsonException $exception) {
    echo $exception->getMessage();
}