<?php declare(strict_types=1);

/*
 * This file is part of the Status Stack package.
 *
 * (c) Lukas Hron <info@lukashron.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StatusStack\DNS;

/**
 * {PROTOCOL}://{SECRET_KEY}@{ENDPOINT}
 */
final class DNSParser
{
    public function parser(string $dns): DNSDTO
    {
        if (preg_match('#^(http|https):\/\/(.*)@(.*)$#', $dns, $result) === false) {
            throw new DNSParserException('Invalid format of DNS.');
        }

        // {SECRET_KEY}
        if (strlen($result[2]) === 0) {
            throw new DNSParserException('Secret key is not set in DNS.');
        }

        // {ENDPOINT}
        if (strlen($result[3]) === 0) {
            throw new DNSParserException('Endpoint is not set in DNS.');
        }

        return new DNSDTO($result[1], $result[2], $result[3]);
    }
}