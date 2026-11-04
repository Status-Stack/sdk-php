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
use LogicException;
use StatusStack\Dsn\DsnDto;
use StatusStack\StatusStack;
use function curl_errno;
use function curl_error;
use function curl_exec;
use function curl_getinfo;
use function curl_init;
use function curl_setopt_array;
use function extension_loaded;
use function is_string;
use function sprintf;
use function substr;
use function trim;

final class CurlTransport implements TransportInterface
{
    private float $timeout;

    /** @var resource|\CurlHandle|null */
    private $handle = null;

    public function __construct(float $timeout = 5)
    {
        if (!extension_loaded('curl')) {
            throw new LogicException('CurlTransport requires the "curl" PHP extension.');
        }

        if ($timeout <= 0) {
            throw new InvalidArgumentException('Timeout must be greater than 0.');
        }

        $this->timeout = $timeout;
    }

    public function send(DsnDto $dsn, string $payload): void
    {
        $handle = $this->getHandle();
        $timeoutMs = (int)($this->timeout * 1000);

        curl_setopt_array($handle, [
            CURLOPT_URL => $dsn->getUrl(),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json; charset=UTF-8',
                'User-Agent: statusstack-sdk-php/' . StatusStack::VERSION,
                'X-StatusStack-Auth: ' . $dsn->getSecretKey(),
                // Prevent cURL from waiting for "100 Continue" on larger payloads
                'Expect:'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT_MS => $timeoutMs,
            CURLOPT_CONNECTTIMEOUT_MS => $timeoutMs,
            // Required for sub-second timeouts on some systems
            CURLOPT_NOSIGNAL => true
        ]);

        $body = curl_exec($handle);

        if (!is_string($body)) {
            throw new TransportException(sprintf(
                'Unable to send request: %s (cURL error %d)',
                curl_error($handle),
                curl_errno($handle)
            ));
        }

        $statusCode = (int)curl_getinfo($handle, CURLINFO_RESPONSE_CODE);

        if ($statusCode < 200 || $statusCode >= 300) {
            $excerpt = substr(trim($body), 0, 500);

            throw new TransportException(
                sprintf('Request failed with HTTP status %d: %s', $statusCode, $excerpt),
                $statusCode
            );
        }
    }

    /**
     * @return resource|\CurlHandle
     *
     * @throws TransportException
     */
    private function getHandle()
    {
        if ($this->handle === null) {
            $handle = curl_init();

            if ($handle === false) {
                throw new TransportException('Unable to initialize cURL.');
            }

            $this->handle = $handle;
        }

        return $this->handle;
    }
}
