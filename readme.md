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
|Default        | 86400                                             |

## API

- [(class) CacheService](./source/CacheService.php)

## Usage

You can add the following options to your  `.env` file:

```
ENVIRONMENT=PRODUCTION
CACHE_DIRECTORY=tmp
CACHE_TIMEOUT=900
```

Import the module when creating your application:

```php
require __DIR__ . "/../vendor/autoload.php";

use Papi\PapiBuilder;
use Papimod\Dotenv\DotEnvModule;
use Papimod\Cache\CacheModule;
use function DI\create;

define("PAPI_DOTENV_DIRECTORY", __DIR__); # Optionnal
define("PAPI_DOTENV_FILE", ".env"); # Optionnal

$builder = new PapiBuilder();

$builder->setModule(
        DotEnvModule::class,
        CacheModule::class
    )
    ->build()
    ->run();
```

Use the dedicated service anywhere:

```php
final class MyService
{
    private readonly CacheService $cache_service;

    public function __construct(CacheService $cache_service)
    {
        $this->cache_service = $cache_service;
    }

    public increment(): string
    {
        $foo = $this->cache_service->get('foo');

        if($foo !== null) {
            $foo++
        } else {
            $foo = 1;
        }

        $this->cache_service->set('foo', $foo, 120);
    }
}
```