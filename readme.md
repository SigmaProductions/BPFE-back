## BIK HUB API GATEWAY-AGGREGATOR

### About
This service is intended to be a easier way to aquire multiple data from BIK HUB API in same time, and unify communication both on request and response side.
It is developed using [Swoole](www.swoole.co.uk) as a quick and modern PHP extension, with [Symfony](https://symfony.com/) as a framework. Beside collecting date, it also provides a cache layer using [Redis](https://redis.io/).

### Getting Started
With docker running this service is as simple as:
```
docker-compose up
```
Then all endpoints from openapi.yml file should be available on http://localhost:8000