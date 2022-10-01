<?php declare(strict_types=1);

namespace StatusStack\Dns;

use Nette\StaticClass;

/**
 * Data source name resolver
 *
 * {PROTOCOL}://{SECRET_KEY}@{ENDPOINT}
 */
final class DnsResolver
{
    use StaticClass;

    /**
     * @throws DnsResolverException
     */
    public static function parseDNS(string $dns): array
    {
        if (preg_match('#^(http|https):\/\/(.*)@(.*)$#', $dns, $result) === false) {
            throw new DnsResolverException('DNS record is not be parse.');
        }

        // {SECRET_KEY}
        if ((string) $result[2] === '') {
            throw new DnsResolverException('Secret key is not set in DNS.');
        }

        // {ENDPOINT}
        if ((string) $result[3] === '') {
            throw new DnsResolverException('API endpoint is not set in DNS.');
        }

        return $result;
    }

    /**
     * @throws DnsResolverException
     */
    public static function getHostFromDns(string $dns): string
    {
        $pDNS = self::parseDNS($dns);
        return sprintf('%s://%s', $pDNS[1], $pDNS[3]);
    }

    /**
     * @throws DnsResolverException
     */
    public static function getKeyFromDns(string $dns): string
    {
        return self::parseDNS($dns)[2];
    }
}