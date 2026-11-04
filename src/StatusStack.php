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

use DateTimeInterface;
use StatusStack\Dsn\Dsn;
use StatusStack\Dsn\DsnDto;
use StatusStack\Json\Json;
use StatusStack\Ticket\Ticket;
use StatusStack\Transport\StreamTransport;
use StatusStack\Transport\TransportInterface;

final class StatusStack
{
    public const VERSION = '2.0.0';

    private DsnDto $dsn;
    private TransportInterface $transport;
    private Json $json;

    /** @var (callable(StatusStackException): void)|null */
    private $errorHandler;

    public function __construct(
        DsnDto $dsn,
        ?TransportInterface $transport = null,
        ?callable $errorHandler = null
    )
    {
        $this->dsn = $dsn;
        $this->transport = $transport ?? new StreamTransport();
        $this->json = new Json();
        $this->errorHandler = $errorHandler;
    }

    /**
     * @param string $dsn
     * @param TransportInterface|null $transport
     * @param (callable(StatusStackException): void)|null $errorHandler
     *
     * @return StatusStack
     */
    public static function create(
        string $dsn,
        ?TransportInterface $transport = null,
        ?callable $errorHandler = null
    ): self
    {
        return new self((new Dsn())->parse($dsn), $transport, $errorHandler);
    }

    /**
     * @throws StatusStackException
     */
    public static function capture(string $dsn, string $tag, string $data): void
    {
        self::create($dsn)->send($tag, $data);
    }

    /**
     * @throws StatusStackException When delivery fails and no error handler is set
     */
    public function send(string $tag, string $data, ?DateTimeInterface $dateTime = null): bool
    {
        $ticket = new Ticket($tag, $data, $dateTime);

        return $this->sendTicket($ticket);
    }

    /**
     * @throws StatusStackException When delivery fails and no error handler is set
     */
    public function sendTicket(Ticket $ticket): bool
    {
        try {

            $payload = $this->json->encode($ticket->toArray());
            $this->transport->send($this->dsn, $payload);

        } catch (StatusStackException $e) {

            if ($this->errorHandler === null) {
                throw $e;
            }

            ($this->errorHandler)($e);

            return false;
        }

        return true;
    }
}
