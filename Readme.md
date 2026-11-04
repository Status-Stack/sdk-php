# Status Stack

Install
-------
```bash
  $ composer require statusstack/sdk-php
```

Usage
-----
DSN format: `{PROTOCOL}://{SECRET_KEY}@{ENDPOINT}`

### Static shortcut
```php
use StatusStack\StatusStack;

StatusStack::capture(
    'https://exampleKey1234@platform.statusstack.app/api/v1/ticket/{uuid}',
    'sdk',
    'Example data'
);
```

### Instance (reusable, configurable)

```php
use StatusStack\Dsn\Dsn;
use StatusStack\StatusStack;
use StatusStack\StatusStackException;
use StatusStack\Ticket\Ticket;
use StatusStack\Transport\StreamTransport;

$statusStack = new StatusStack(
    (new Dsn())->parse('https://exampleKey1234@platform.statusstack.app/api/v1/ticket/{uuid}'),
    new StreamTransport(3.0),            // optional, timeout in seconds (default 5)
    static function (StatusStackException $e): void {
        error_log($e->getMessage());     // optional, errors go here instead of being thrown
    }
);

$statusStack->send('sdk', 'Example data');
$statusStack->sendTicket(new Ticket('sdk', 'Example data'));

// optional time of the data (RFC 3339), defaults to the time of receipt
$statusStack->send('cpu-usage', '42.5', new DateTimeImmutable('2026-11-04 10:15:30'));
```

### Sending multiple tickets over one connection
`StreamTransport` opens a new connection for every ticket. `CurlTransport` (requires `ext-curl`)
keeps the connection open, so consecutive tickets sent through the same instance reuse it.

```php
use StatusStack\StatusStack;
use StatusStack\Transport\CurlTransport;

$statusStack = StatusStack::create(
    'https://exampleKey1234@platform.statusstack.app/api/v1/ticket/{uuid}',
    new CurlTransport(3.0)               // optional, timeout in seconds (default 5)
);

foreach ($metrics as $tag => $value) {
    $statusStack->send($tag, $value);
}
```

### Exceptions
All SDK exceptions implement `StatusStack\StatusStackException`:
- `StatusStack\Dsn\InvalidDsnException` – malformed DSN (always thrown)
- `StatusStack\Json\EncodingException` – payload cannot be encoded to JSON
- `StatusStack\Transport\TransportException` – network error or non-2xx response (`getStatusCode()`)

Develop
-------
```bash
  $ docker-compose up -d --build
  $ docker-compose exec sdk composer install
  
  # Rector
  $ docker-compose exec sdk php vendor/bin/rector process
```

Copyright (c) 2022-2026 Status Stack