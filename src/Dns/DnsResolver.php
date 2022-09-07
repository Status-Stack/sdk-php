<?php declare(strict_types=1);

namespace StatusStack\Dns;

use Nette\StaticClass;

final class DnsResolver
{
    use StaticClass;

    /**
     * @throws DnsResolverException
     */
    public static function parseDNS(string $dns): array
    {
        if (preg_match('#((http|https):\/\/.*)@(.*)#', $dns, $result) === false) {
            throw new DnsResolverException('DNS record is not be parse.');
        }

        if (!isset($result[1]) && !is_string($result[1])) {
            throw new DnsResolverException('Host is not set in DNS.');
        }

        if (!isset($result[3]) && !is_string($result[3])) {
            throw new DnsResolverException('Key is not set in DNS.');
        }

        return $result;
    }

    /**
     * @throws DnsResolverException
     */
    public static function getHostFromDns(string $dns): string
    {
        return self::parseDNS($dns)[1];
    }

    /**
     * @throws DnsResolverException
     */
    public static function getKeyFromDns(string $dns): string
    {
        return self::parseDNS($dns)[3];
    }
}