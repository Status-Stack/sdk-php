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

use StatusStack\Dsn\DsnDto;

interface TransportInterface
{
    /**
     * Sends JSON payload to the endpoint defined by DSN.
     *
     * @throws TransportException when the request fails or the response is not 2xx
     */
    public function send(DsnDto $dsn, string $payload): void;
}
