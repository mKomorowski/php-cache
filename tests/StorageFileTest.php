<?php

use mKomorowski\Cache\StorageFile;

/**
 * Class StorageFileTest
 */

class StorageFileTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */

    protected $storageFile;

    /**
     * Set Up Storage object
     */

    public function setUp()
    {
        $this->storageFile = new StorageFile(realpath(__DIR__.'/cache'));
    }

    /**
     * Assert if the invalid path will throw the StorageFile Exception
     * @expectedException \mKomorowski\Cache\StorageFileException
     */

    public function testInvalidPathThrowException()
    {
        $storageFile = new StorageFile(realpath(__DIR__.'/invalid'));

        $this->assertFalse($storageFile->set('key', 'value'));
    }

    /**
     * Assert if the file with the value is successfully created
     */

    public function testSetFunctionCreateKeyReturnTrue()
    {
        $this->assertTrue($this->storageFile->set('key', 'value'));
    }

    /**
     * Assert if the value is correctly read
     */

    public function testGetFunctionReturnCorrectValue()
    {
        $this->assertEquals('value', $this->storageFile->get('key'));

        $this->assertNull($this->storageFile->get('value'));
    }

    /**
     * Assert if the value is correctly read with expire time set
     */

    public function testGetFunctionExpired()
    {
        $this->storageFile->set('key', 'value', 1);

        $this->assertEquals('value', $this->storageFile->get('key'));

        sleep(2);

        $this->assertNull($this->storageFile->get('key'));
    }

    /**
     * Assert if has function return correct value
     */

    public function testHasReturnCorrectValue()
    {
        $this->storageFile->set('key', 'value');

        $this->assertTrue($this->storageFile->has('key'));

        $this->assertFalse($this->storageFile->has('another_key'));
    }
}