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

final class Ticket
{
    private string $tag;
    private string $data;

    public function __construct(
        string $tag,
        string $data
    )
    {
        $this->tag = $tag;
        $this->data = $data;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'tag' => $this->tag,
            'data' => $this->data,
        ];
    }
}
