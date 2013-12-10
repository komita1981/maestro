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

use Komita\SiderBundle\Redis\DatatypeAbstract;
use Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException;

/**
 * CRUD operations for HASH datatype
 *
 * Class DatatypeHash
 * @package Komita\SiderBundle\Redis
 */
class DatatypeHash extends DatatypeAbstract
{
    /**
     * Sets fields and its values in Redis hash
     *
     * @param null $data
     *
     * @throws Exception\DatatypeInvalidArgumentException
     *
     * @return mixed
     */
    public function setValue($data = null)
    {
        if (! is_array($data) or empty($data)){
            throw new DatatypeInvalidArgumentException('Provide fields and values for hash');
        }

        return $this->redis_client->hmset($this->key_name, $data);
    }

    /**
     * Returns keys and values if there is no options provided
     * If fields are provided then function returns it's values
     *
     * @param null $options
     *
     * @throws DatatypeInvalidArgumentException
     *
     * @return mixed
     */
    public function getValue($options = null)
    {
        if (is_null($options)){
            return $this->redis_client->hgetall($this->key_name);
        }

        ! is_array($options) and $options['fields'] = $options;

        if (! isset($options['fields'])){
            throw new DatatypeInvalidArgumentException('Provide fields to get it\'s values');
        }

        return $this->redis_client->hmget($this->key_name, $options['fields']);
    }

    /**
     * Delete key or provided fields
     * @param null $data
     *
     * @return mixed
     */
    public function delete($data = null)
    {
        if (is_null($data)){
            return $this->redis_client->del($this->key_name);
        }

        return $this->redis_client->hdel($this->key_name, $data);
    }

    /**
     * Returns number of fields inside an hash key
     *
     * @return int
     */
    public function getCount()
    {
        return $this->redis_client->hlen($this->key_name);
    }
}