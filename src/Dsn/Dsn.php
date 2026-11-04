<?php declare(strict_types=1);

/*
 * This file is part of the Status Stack package.
 *
 * (c) Lukas Hron <info@lukashron.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StatusStack\Dsn;

use function in_array;
use function parse_url;
use function rawurldecode;
use function strtolower;

/**
 * Parses connection string in format {PROTOCOL}://{SECRET_KEY}@{ENDPOINT}
 *
 * Example: https://secretKey@platform.statusstack.app/api/v1/ticket/{uuid}
 */
final class Dsn
{
    /**
     * @throws InvalidDsnException
     */
    public function parse(string $dsn): DsnDto
    {
        $parts = parse_url(trim($dsn));

        if ($parts === false || !isset($parts['scheme'], $parts['host'])) {
            throw new InvalidDsnException('Invalid format of DSN.');
        }

        return new DsnDto(
            $this->buildUrl($parts),
            $this->extractSecretKey($parts)
        );
    }

    /**
     * @param array<string, int|string> $parts Result of parse_url()
     */
    private function buildUrl(array $parts): string
    {
        $scheme = strtolower((string)$parts['scheme']);

        if (!in_array($scheme, ['http', 'https'], true)) {
            throw new InvalidDsnException(
                sprintf('Unsupported DSN protocol "%s", use http or https.', $parts['scheme'])
            );
        }

        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        $path = $parts['path'] ?? '';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';

        return $scheme . '://' . $parts['host'] . $port . $path . $query;
    }

    /**
     * @param array<string, int|string> $parts Result of parse_url()
     */
    private function extractSecretKey(array $parts): string
    {
        $secretKey = rawurldecode((string)($parts['user'] ?? ''));

        // A colon inside the key is parsed by parse_url() as "user:pass"
        if (isset($parts['pass'])) {
            $secretKey .= ':' . rawurldecode((string)$parts['pass']);
        }

        if ($secretKey === '') {
            throw new InvalidDsnException('Secret key is not set in DSN.');
        }

        return $secretKey;
    }
}
