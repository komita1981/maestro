<?php

/*
* This file is part of the Sider package.
*
* (c) Milan Popovic - <komita1981@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Komita\SiderBundle\Redis;
use Komita\SiderBundle\Redis\Exception\KeyDoesNotExistsException;

/**
 * This abstract class should be extended by all class that represents different datatypes.
 * Intend of this group of classes is to be basic CRUD for all datatypes
 *
 * Class DatatypeAbstract
 * @package Komita\SiderBundle\Redis
 */
abstract class DatatypeAbstract
{
    /**
     * Redis client
     *
     * @var Redis
     */
    protected $redis_client;

    /**
     * Key name
     *
     * @var string
     */
    protected $key_name;

    /**
     * Datatype
     *
     * @var string
     */
    protected $datatype;

    /**
     * Time till key leave
     * @var int
     */
    protected $ttl;

    /**
     * Database of the key
     * @var int
     */
    protected $database;

    /**
     * Class constructor
     *
     * @param $key_name
     * @param $redis_client
     * @param $database
     *
     * @throws KeyDoesNotExistsException
     */
    public function __construct($key_name, $redis_client, $database){
        $this->key_name = $key_name;
        $this->redis_client = $redis_client;
        $this->datatype = $this->redis_client->type($this->key_name);
        $this->database = $database;
    }

    /**
     * Returns keys value
     *
     * @param mixed $options
     *
     * @return mixed
     */
    abstract public function getValue($options = null);

    /**
     * Deletes key or some part of key values
     *
     * @param mixed $options
     *
     * @return mixed
     */
    abstract public function delete($options = null);

    /**
     * Sets data for key value
     *
     * @param mixed $data
     */
    abstract public function setValue($data);

    /**
     * Returns number of members (hashes, sorted sets, sets, lists) or number of chars (strings)
     *
     * @return int
     */
    abstract public function getCount();

    /**
     * Return keys name
     *
     * @return string
     */
    public function getName()
    {
        return $this->key_name;
    }

    /**
     * Return keys name
     *
     * @return string
     */
    public function getDatatype()
    {
        return $this->datatype;
    }

    /**
     * Return database
     *
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Returns keys time till live
     *
     * @return int
     */
    public function getTtl()
    {
        ! isset($this->ttl) and $this->ttl = $this->redis_client->ttl($this->key_name);

        return $this->ttl;
    }
}