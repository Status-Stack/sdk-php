# Status Stack

Install
-------
```bash
  $ composer require statusstack/sdk-php
```

Usage
-----
```php
    StatusStack::capture(
        'http://nginx@exampleKey1234',
        'sdk',
        'Example data'
    );
```

Develop
-------
```bash
  $ docker-compose up -d --build
  $ docker-compose exec sdk composer install
  
  # Rector
  $ docker-compose exec sdk php vendor/bin/rector process
```

Copyright (c) 2022-2025 Status Stack