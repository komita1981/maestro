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
 * CRUD operations for LIST datatype
 *
 * Class DatatypeHash
 * @package Komita\SiderBundle\Redis
 */
class DatatypeList extends DatatypeAbstract
{
    /**
     * Insert new element or array of elements
     *
     * @param null $data
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

        $on_head = isset($data['head']) ? $data['head'] : false;
        $on_tail = isset($data['tail']) ? $data['tail'] : false;

        if ($on_head == $on_tail){
            throw new DatatypeInvalidArgumentException('Please define where to insert element in list for key '.$this->key_name);
        }

        $method = $on_head == true ? 'lpush' : 'rpush';

        if (! isset($data['value'])){
            throw new DatatypeInvalidArgumentException('Please define new element in list for key '.$this->key_name);
        }

        $values = $data['value'];
        ! is_array($values) and $values = array($values);
        $arguments[] = $this->key_name;
        $arguments = array_merge($arguments, $values);

        return call_user_func_array(array($this->redis_client, $method), $arguments);
    }
    /**
     * Returns elements from list in given range
     *
     * @param null $options
     *
     * @throws Exception\DatatypeInvalidArgumentException
     *
     * @return mixed
     */
    public function getValue($options = null)
    {
        $start = isset($options['start']) ? $options['start'] : 0;
        $end = isset($options['end']) ? $options['end'] : -1;

        if (! is_int($start) or ! is_int($end)){
            throw new DatatypeInvalidArgumentException('Invalid arguments for getting value');
        }

        return $this->redis_client->lrange($this->key_name, $start, $end);
    }

    /**
     * Returns number of elements in list
     *
     * @return int
     */
    public function getCount()
    {
        return $this->redis_client->lLen($this->key_name);
    }

    /**
     * Deletes key or element on tail or head
     *
     * @param null $options
     *
     * @throws Exception\DatatypeInvalidArgumentException
     *
     * @return mixed
     */
    public function delete($options = null)
    {
        if (is_null($options)){
            return $this->redis_client->del($this->key_name);
        }

        if (isset($options['head']) and $options['head'] == true){
            return $this->redis_client->lpop($this->key_name);
        }

        if (isset($options['tail']) and $options['tail'] == true){
            return $this->redis_client->rpop($this->key_name);
        }

        throw new DatatypeInvalidArgumentException('Invalid arguments for delete');
    }
}