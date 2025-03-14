<?php declare(strict_types=1);

namespace StatusStack\Dsn;

final class DsnDTO
{
    public string $protocol;
    public string $secretKey;
    public string $endpoint;

    public function __construct(
        string $protocol,
        string $secretKey,
        string $endpoint
    )
    {
        $this->protocol = $protocol;
        $this->secretKey = $secretKey;
        $this->endpoint = $endpoint;
    }
}
