<?php declare(strict_types=1);

/*
 * This file is part of the Status Stack package.
 *
 * (c) Lukas Hron <info@lukashron.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StatusStack\Transport;

use InvalidArgumentException;
use StatusStack\Dsn\DsnDto;
use StatusStack\StatusStack;
use function fclose;
use function fopen;
use function implode;
use function is_string;
use function preg_match;
use function restore_error_handler;
use function set_error_handler;
use function sprintf;
use function stream_get_contents;
use function stream_get_meta_data;
use function substr;
use function trim;

final class StreamTransport implements TransportInterface
{
    private float $timeout;

    public function __construct(float $timeout = 5)
    {
        if ($timeout <= 0) {
            throw new InvalidArgumentException('Timeout must be greater than 0.');
        }

        $this->timeout = $timeout;
    }

    public function send(DsnDto $dsn, string $payload): void
    {
        $context = $this->createContext($dsn, $payload);

        [$statusCode, $body] = $this->post($dsn->getUrl(), $context);

        if ($statusCode < 200 || $statusCode >= 300) {
            $excerpt = substr(trim($body), 0, 500);

            throw new TransportException(
                sprintf('Request failed with HTTP status %d: %s', $statusCode, $excerpt),
                $statusCode
            );
        }
    }

    /**
     * @return resource
     */
    private function createContext(DsnDto $dsn, string $payload)
    {
        $headers = [
            'Content-Type: application/json; charset=UTF-8',
            'User-Agent: statusstack-sdk-php/' . StatusStack::VERSION,
            'X-StatusStack-Auth: ' . $dsn->getSecretKey()
        ];

        return stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", $headers),
                'content' => $payload,
                'timeout' => $this->timeout,
                // Return the response for 4xx/5xx too, so the status code can be checked
                'ignore_errors' => true
            ],
        ]);
    }

    /**
     * @param resource $context
     *
     * @return array{0: int, 1: string} Status code and response body
     *
     * @throws TransportException
     */
    private function post(string $url, $context): array
    {
        // fopen() reports network errors as PHP warnings, catch them to build the exception message
        $error = 'unknown error';
        set_error_handler(static function (int $severity, string $message) use (&$error): bool {
            $error = $message;

            return true;
        });

        try {
            $stream = fopen($url, 'rb', false, $context);
        } finally {
            restore_error_handler();
        }

        if ($stream === false) {
            throw new TransportException('Unable to send request: ' . $error);
        }

        try {
            $headers = stream_get_meta_data($stream)['wrapper_data'] ?? [];
            $body = (string)stream_get_contents($stream);
        } finally {
            fclose($stream);
        }

        return [$this->parseStatusCode($headers), $body];
    }

    /**
     * Takes the last status line, so redirects resolve to the final response.
     *
     * @param mixed $headers Raw response headers from stream meta data
     *
     * @throws TransportException
     */
    private function parseStatusCode($headers): int
    {
        $statusCode = null;

        foreach ((array)$headers as $header) {
            if (is_string($header) && preg_match('#^HTTP/\S+\s+(\d{3})#', $header, $match) === 1) {
                $statusCode = (int)$match[1];
            }
        }

        if ($statusCode === null) {
            throw new TransportException('Unable to read HTTP status code from response.');
        }

        return $statusCode;
    }
}
