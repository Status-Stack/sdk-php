<?php declare(strict_types=1);

namespace StatusStack\DNS;

final class DNSDTO
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
