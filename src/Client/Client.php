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

use StatusStack\DNS\DNSDTO;

class Client
{
    const responseHeaderProtocolVersion = '_protocolVersion';
    const responseHeaderStatusCode = '_responseCode';

    public function sendRequest(DNSDTO $dns, string $requestBody): void
    {
        $headers = [
            'Content-type: application/json; charset=UTF-8',
            'User-Agent: Status Stack - php SDK',
            sprintf('X-StatusStack-Auth: %s', $dns->secretKey)
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", $headers)."\r\n",
                'content' => $requestBody
            ]
        ];

        $endpoint = sprintf('%s://%s', $dns->protocol, $dns->endpoint);
        $context = stream_context_create($options);
        $response = file_get_contents($endpoint, false, $context);

        if ($response === false) {
            throw new ClientException('Error send request.');
        }

        $responseHeaders = $this->parseHeadersToAssociativeArray($http_response_header);
        $responseStatusCode = $responseHeaders[self::responseHeaderStatusCode];

        if ($responseStatusCode !== 201) {
            throw new ClientException(
                sprintf('Fatal error send request. Response status code: %s', $responseStatusCode)
            );
        }
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
                $headers[self::responseHeaderProtocolVersion] = (int)$output[1];
                $headers[self::responseHeaderStatusCode] = (int)$output[2];
            }
        }

        return $headers;
    }
}