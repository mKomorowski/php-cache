<?php namespace mKomorowski\Cache;

/**
 * Interface StorageInterface
 * @package mKomorowski\Cache
 */

interface StorageInterface
{

    /**
     * @param string $key
     * @param string $value
     * @return bool
     */

    public function set($key, $value);

    /**
     * @param $key
     * @return mixed
     */

    public function get($key);

    /**
     * @param $key
     * @return mixed
     */

    public function has($key);
}