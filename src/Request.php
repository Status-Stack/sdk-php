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

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use StatusStack\Definition\Sdk;

class Request implements RequestInterface
{
    private array $headers = [];

    private string $apiEndpoint;

    private string $method;

    private string $data;

    public function __construct(
        string $apiEndpoint,
        string $method,
        string $data
    )
    {
        $this->apiEndpoint = $apiEndpoint;
        $this->method = $method;
        $this->data = $data;
    }

    public function getProtocolVersion()
    {
        // TODO: Implement getProtocolVersion() method.
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
        return $this->headers;
    }

    public function hasHeader($name)
    {
        // TODO: Implement hasHeader() method.
    }

    public function getHeader($name)
    {
        // TODO: Implement getHeader() method.
    }

    public function getHeaderLine($name)
    {
        // TODO: Implement getHeaderLine() method.
    }

    public function withHeader($name, $value)
    {
        // TODO: Implement withHeader() method.
    }

    /**
     * @param $name
     * @param $value
     */
    public function withAddedHeader($name, $value): void
    {
        $this->headers[] = sprintf('%s: %s', $name, $value);
    }

    public function withoutHeader($name)
    {
        // TODO: Implement withoutHeader() method.
    }

    /**
     * @return array
     */
    public function getBody(): string
    {
        return $this->data;
    }

    public function withBody(StreamInterface $body)
    {
        // TODO: Implement withBody() method.
    }

    public function getRequestTarget()
    {
        // TODO: Implement getRequestTarget() method.
    }

    public function withRequestTarget($requestTarget)
    {
        // TODO: Implement withRequestTarget() method.
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod($method)
    {
        // TODO: Implement withMethod() method.
    }

    public function getUri(): string
    {
        return $this->apiEndpoint;
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        // TODO: Implement withUri() method.
    }
}