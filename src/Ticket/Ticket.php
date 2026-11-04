<?php declare(strict_types=1);

/*
 * This file is part of the Status Stack package.
 *
 * (c) Lukas Hron <info@lukashron.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StatusStack\Ticket;

use DateTimeInterface;

final class Ticket
{
    private string $tag;
    private string $data;
    private ?DateTimeInterface $dateTime;

    /**
     * @param string $tag Max 255 characters
     * @param string $data Max 65535 characters, numeric value is stored as number
     * @param DateTimeInterface|null $dateTime Time of the data, defaults to the time of receipt
     */
    public function __construct(
        string $tag,
        string $data,
        ?DateTimeInterface $dateTime = null
    )
    {
        $this->tag = $tag;
        $this->data = $data;
        $this->dateTime = $dateTime;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getDateTime(): ?DateTimeInterface
    {
        return $this->dateTime;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'tag' => $this->tag,
            'data' => $this->data,
        ];

        if ($this->dateTime !== null) {
            $payload['dateTime'] = $this->dateTime->format(DateTimeInterface::RFC3339);
        }

        return $payload;
    }
}
