<?php declare(strict_types=1);

/*
 * This file is part of the Status Stack package.
 *
 * (c) Lukas Hron <info@lukashron.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StatusStack;

use Nette\StaticClass;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use StatusStack\Client\Client;
use StatusStack\Client\ClientException;
use StatusStack\Definition\HttpCodes;
use StatusStack\Definition\RequestMethods;
use StatusStack\Definition\Sdk;
use StatusStack\Dns\Dnsresolver;
use StatusStack\Dns\DnsResolverException;

final class StatusStackFactory
{
    use StaticClass;

    /**
     * @throws ClientException
     * @throws DnsResolverException
     * @throws StatusStackException
     * @throws JsonException
     */
    public static function send(
        string $dns,
        string $tag,
        string $data,
        int $priority = 0
    ): void
    {
        $requestData = Json::encode([
            'tag' => $tag,
            'data' => $data,
            'priority' => $priority
        ]);

        $request = new Request(
            DnsResolver::getHostFromDns($dns),
            RequestMethods::POST,
            $requestData
        );

        $request->withAddedHeader('Content-type', 'application/json; charset=UTF-8');
        $request->withAddedHeader('User-Agent', Sdk::name);
        $request->withAddedHeader(Sdk::authHeaderName, DnsResolver::getKeyFromDns($dns));

        $client = new Client();
        $response = $client->sendRequest($request);

        if ($response->getStatusCode() !== HttpCodes::addItemSuccess) {
            throw new StatusStackException(
                sprintf('Error to send data. Full response: %s', $response->getBody())
            );
        }
    }
}
