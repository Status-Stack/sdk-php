# Status Stack

Develop
-------
```bash
  $ docker-compose up -d --build
  $ docker-compose exec sdk composer install
  
  $ docker-compose exec sdk php examples/sendExampleRequest.php
  
  # Rector
  $ docker-compose exec sdk php vendor/bin/rector process
```