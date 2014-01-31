<?php

namespace Hautelook\Solarium\Cache;

/**
 * @author Adrien Brault <adrien.brault@gmail.com>
 */
class CacheProfile
{
    private $key;
    private $lifetime;

    public function __construct($key, $lifetime)
    {
        if (null === $lifetime) {
            throw new \InvalidArgumentException('You need to give a lifetime for the cache.');
        }

        $this->key = $key;
        $this->lifetime = $lifetime;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function getLifetime()
    {
        return $this->lifetime;
    }
}
