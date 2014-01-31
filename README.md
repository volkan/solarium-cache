# Solarium Cache ![Travis build status](https://travis-ci.org/hautelook/solarium-cache.png)

Solarium Plugin to cache queries using Doctrine Cache.

## Installation

```json
{
    "require": {
        "hautelook/solarium-cache": "0.1@dev"
    }
}
```

## Usage

```php
$client = ...;
$cache = new RedisCache(); // or whichever

$plugin = new CachePlugin();
$plugin->setCache($cache);
$client->registerPlugin('cache', $plugin);

$query = $client->createSelect(array(
    'cache_lifetime' => 60,
));
$result = $client->execute($query);
```

## Usage with the NelmioSolariumBundle

```php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Hautelook\Solarium\Cache\Bundle\HautelookSolariumCacheBundle(),
    );

    // ...

    return $bundles;
}
```

Configure the bundle which solarium client should have the cache plugin configured, along with the doctrine cache
service id.

```
# app/config/config.yml
liip_doctrine_cache:
    namespaces:
        search:
            namespace: search
            type: memcache
            host: ...
            port: ...

hautelook_solarium_cache:
    clients:
        default: liip_doctrine_cache.ns.search
```

In this example we use the [LiipDoctrineCacheBundle](https://github.com/liip/LiipDoctrineCacheBundle) to create a
doctrine cache service.
