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

use Komita\SiderBundle\Redis;
use Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException;

/**
 * CRUD operations for SET datatype
 *
 * Class DatatypeHash
 * @package Komita\SiderBundle\Redis
 */
class DatatypeSet extends DatatypeAbstract
{
    /**
     * @param $data
     *
     * Inserts one or more members to the set
     *
     * @throws Exception\DatatypeInvalidArgumentException
     *
     * @return mixed
     */
    public function setValue($data = null)
    {
        if (empty($data)){
            throw new DatatypeInvalidArgumentException('You have to provide non empty value or array for key '.$this->key_name);
        }
        $arguments[] = $this->key_name;
        ! is_array($data) and $data = array($data);
        $arguments = array_merge($arguments, $data);

        return call_user_func_array(array($this->redis_client, 'sadd'), $arguments);
    }

    /**
     * Returns all members of key
     *
     * @param $options
     *
     * @return mixed
     */
    public function getValue($options = null)
    {
        return $this->redis_client->smembers($this->key_name);
    }

    /**
     * Deletes key or member by member
     *
     * @param null $members
     *
     * @return mixed
     */
    public function delete($members = null)
    {
        if (is_null($members)){
            return $this->redis_client->del($this->key_name);
        }

        $arguments[] = $this->key_name;

        if (is_array($members)){
            $arguments = array_merge($arguments, $members);
        } else{
            $arguments[] = $members;
        }

        return call_user_func_array(array($this->redis_client, 'srem'), $arguments);
    }

    /**
     * Returns number of elements in string
     *
     * @return int
     */
    public function getCount()
    {
        return $this->redis_client->scard($this->key_name);
    }
}