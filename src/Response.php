<?php declare(strict_types=1);

namespace StatusStack;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    private array $httpResponseHeader;
    private string $response;

    public function __construct(
        array $httpResponseHeader,
        string $response
    )
    {
        $this->httpResponseHeader = $httpResponseHeader;
        $this->response = $response;
    }

    public function getProtocolVersion(): ?string
    {
        return $this->getHeader('_protocolVersion');
    }

    public function withProtocolVersion($version)
    {
        // TODO: Implement withProtocolVersion() method.
    }

    /**
     * @return array|string[][]
     */
    public function getHeaders(): array
    {
        return $this->httpResponseHeader;
    }

    /**
     * @param $name
     */
    public function hasHeader($name): bool
    {
        return isset($this->httpResponseHeader[$name]);
    }

    /**
     * @param $name
     */
    public function getHeader($name): ?string
    {
        return $this->httpResponseHeader[$name] ?? null;
    }

    public function getHeaderLine($name)
    {
        // TODO: Implement getHeaderLine() method.
    }

    public function withHeader($name, $value)
    {
        // TODO: Implement withHeader() method.
    }

    public function withAddedHeader($name, $value)
    {
        // TODO: Implement withAddedHeader() method.
    }

    public function withoutHeader($name)
    {
        // TODO: Implement withoutHeader() method.
    }

    public function getBody(): string
    {
        return $this->response;
    }

    public function withBody(StreamInterface $body)
    {
        // TODO: Implement withBody() method.
    }

    public function getStatusCode(): int
    {
        // return (int)$this->getHeader('_responseCode');
        return $this->httpResponseHeader['_responseCode'];
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        // TODO: Implement withStatus() method.
    }

    public function getReasonPhrase()
    {
        // TODO: Implement getReasonPhrase() method.
    }
}