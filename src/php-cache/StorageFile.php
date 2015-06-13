<?php namespace mKomorowski\Cache;

use stdClass;

class StorageFile implements StorageInterface
{
    protected $cacheDir;

    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * @return string
     */

    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     * Create file with value
     * @param string $key
     * @param string $value
     * @param int|null $expire_at
     * @return bool
     * @throws StorageFileException
     */

    public function set($key, $value, $expire_at = null)
    {
        $filePath = $this->createFilePath($key);

        if(!$this->createDirectory(dirname($filePath))) return false;

        $this->save($filePath, $value, $expire_at);

        return true;
    }

    public function has($key)
    {
        return (bool)$this->get($key);
    }

    /**
     * Get value from file
     * @param string $key
     * @return mixed|null
     */

    public function get($key)
    {
        $filePath = $this->createFilePath($key);

        if(!is_file($this->cacheDir.'/'.$filePath) || !is_readable($this->cacheDir.'/'.$filePath)) return null;

        $storageClass = unserialize(file_get_contents($this->cacheDir.'/'.$filePath));

        if($this->checkIfExpired($storageClass->expire_at)){

            return null;
        }

        return $storageClass->value;
    }

    /**
     * Check if cache key have expired
     * @param int|null $time
     * @return bool
     */

    protected function checkIfExpired($time)
    {
        return ($time && (time() > $time));
    }

    /**
     * Serialize stdClass with value and expiration time
     * @param $path
     * @param $value
     * @param $expire_at
     */

    protected function save($path, $value, $expire_at)
    {
        $storageClass = new stdClass;

        $storageClass->expire_at = ($expire_at) ? ($expire_at + time()) : $expire_at;

        $storageClass->value = $value;

        file_put_contents($this->cacheDir.'/'.$path, serialize($storageClass));
    }

    /**
     * Create cache directory
     * @param string $dir
     * @return bool
     * @throws StorageFileException
     */

    protected function createDirectory($dir)
    {
        if(!is_dir($this->cacheDir) || !is_readable($this->cacheDir)) throw New StorageFileException('Directory is not writable');

        // If key is already set
        if(file_exists($this->cacheDir.'/'.$dir)) return true;

        return mkdir($this->cacheDir.'/'.$dir, 0777);
    }

    /**
     * Create cache directory and file name for given key
     * @param string $key
     * @return string
     */

    protected function createFilePath($key)
    {
        $hash = md5($key);

        return substr($hash, 0, 8).'/'.$hash;
    }
}