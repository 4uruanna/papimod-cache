# Cache Papi Module

![]( https://img.shields.io/badge/php-8.5-777BB4?logo=php)
![]( https://img.shields.io/badge/composer-2-885630?logo=composer)

## Description

Enable [routes](https://www.slimframework.com/docs/v4/objects/routing.html#route-expressions-caching) and [DI](https://php-di.org/doc/performances.html#setup) caching in your [papi](https://github.com/4uruanna/papi) in production.

By default you should add `.cache/` into your .gitignore file

I also recommend reading the section "[deployment in production](https://php-di.org/doc/performances.html#deployment-in-production)".

## Prerequisites Modules

- [Dotenv](https://github.com/4uruanna/papimod-dotenv)

## Configuration

### `ENVIRONMENT` (.ENV)

|               |                                                   |
|-:             |:-                                                 |
|Required       | No                                                |
|Type           | `PRODUCTION`, `DEVELOPMENT`, `TEST` or `null`     |
|Description    | Enable caching when set to `PRODUCTION`           |
|Default        | `null`                                            |

### `CACHE_DIRECTORY` (.ENV)

|               |                                                   |
|-:             |:-                                                 |
|Required       | No                                                |
|Type           | string                                            |
|Description    | Cache directory path                              |
|Default        | `{{project_directory}}/.cache`                    |

### `CACHE_TIMEOUT` (.ENV)

|               |                                                   |
|-:             |:-                                                 |
|Required       | No                                                |
|Type           | Int                                               |
|Description    | Time in seconds between cache refreshes           |
|Default        | 900                                               |

## Definition

### CacheService

Manages a cache stored in a JSON file. It handles automatic cache expiration based on a `CACHE_TIMEOUT` value and provides methods to get and set cached values.
