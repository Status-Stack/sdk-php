<?php declare(strict_types=1);

/*
 * This file is part of the Status Stack package.
 *
 * (c) Lukas Hron <info@lukashron.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StatusStack\Client;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use StatusStack\Response;

class Client implements ClientInterface
{
    /**
     * @throws ClientException
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $options = [
            'http' => [
                'method' => $request->getMethod(),
                'header' => implode("\r\n", $request->getHeaders())."\r\n",
                'content' => $request->getBody()
            ]
        ];

        $response = @file_get_contents(
            (string)$request->getUri(),
            false,
            stream_context_create($options)
        );

        if ($response === false) {
            throw new ClientException('Error send request.');
        }

        if (strlen($response) == 0) {
            throw new ClientException('Response is empty.');
        }

        return new Response(
            $this->parseHeadersToAssociativeArray($http_response_header),
            $response
        );
    }

    /**
     * https://www.php.net/manual/en/reserved.variables.httpresponseheader.php#117203
     */
    private function parseHeadersToAssociativeArray(array $headers): array
    {
        foreach ($headers as $header) {
            $t = explode(':', $header, 2);
            if (isset($t[1])) {
                $headers[trim($t[0])] = trim($t[1]);
            } elseif (preg_match('#HTTP\/([0-9\.]+)\s+(\d+)#', $header, $output)) {
                $headers['_protocolVersion'] = (int) $output[1];
                $headers['_responseCode'] = (int) $output[2];
            }
        }

        return $headers;
    }
}