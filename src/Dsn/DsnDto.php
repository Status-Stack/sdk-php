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

final class DsnDto
{
    private string $url;
    private string $secretKey;

    public function __construct(
        string $url,
        string $secretKey
    )
    {
        $this->url = $url;
        $this->secretKey = $secretKey;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function __debugInfo(): array
    {
        return [
            'url' => $this->url,
            'secretKey' => '***'
        ];
    }
}
