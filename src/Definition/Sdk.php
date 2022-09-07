<?php declare(strict_types=1);

namespace StatusStack\Definition;

class Sdk
{
    /**
     * SDK
     * @var string
     */
    const name = 'Status Stack - php SDK';

    /**
     * API
     * @var string
     */
    const apiEndpointDataPost = '/api/v1/data/';

    /**
     * @var string
     */
    const authHeaderName = 'X-StatusStack-Auth';
}