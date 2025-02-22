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

use StatusStack\Client\Client;
use StatusStack\DNS\DNSParser;
use StatusStack\Json\Json;

final class StatusStack
{
    public static function capture(
        string $dns,
        string $tag,
        string $data
    ): void
    {
        $requestData = Json::encode([
            'tag' => $tag,
            'data' => $data
        ]);

        $DNSParser = new DNSParser();
        $DNSDTO = $DNSParser->parser($dns);

        $client = new Client();
        $client->sendRequest($DNSDTO, $requestData);
    }
}
