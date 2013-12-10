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

/**
 * CRUD operations for STRING datatype
 *
 * Class DatatypeString
 * @package Komita\SiderBundle\Redis
 */
class DatatypeString extends DatatypeAbstract
{
    /**
     * Sets new string key value
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->redis_client->set($this->key_name, $value);
    }

    /**
     * Returns string key value
     *
     * @param null $options
     *
     * @return mixed
     */
    public function getValue($options = null)
    {
        return $this->redis_client->get($this->key_name);
    }

    /**
     * Deletes string key
     *
     * @param null $data
     *
     * @return mixed
     */
    public function delete($data = null)
    {
        return $this->redis_client->del($this->key_name);
    }

    /**
     * Returns length of string
     *
     * @return int
     */
    public function getCount()
    {
        return $this->redis_client->strlen($this->key_name);
    }
}