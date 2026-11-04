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

use RuntimeException;
use StatusStack\StatusStackException;
use Throwable;

final class TransportException extends RuntimeException implements StatusStackException
{
    private ?int $statusCode;

    public function __construct(
        string $message,
        ?int $statusCode = null,
        ?Throwable $previous = null
    )
    {
        parent::__construct($message, $statusCode ?? 0, $previous);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }
}
