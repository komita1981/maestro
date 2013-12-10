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
 * CRUD operations for ZSET datatype
 *
 * Class DatatypeZset
 * @package Komita\SiderBundle\Redis
 */
class DatatypeZset extends DatatypeAbstract
{
    /**
     * Inserts new member with score into sorted set
     *
     * @param array $data
     *
     * @throws Exception\DatatypeInvalidArgumentException
     */
    public function setValue($data = null)
    {
        if (! is_array($data) or empty($data)){
            throw new DatatypeInvalidArgumentException('You have to provide non empty array for key '.$this->key_name);
        }

        foreach($data as $member => $score){
            if (! is_int($score)){
                throw new DatatypeInvalidArgumentException('Invalid score for member '.$member.' - '.$score.' for key '.$this->key_name);
            }
        }

        $this->redis_client->zadd($this->key_name, $data);
    }

    /**
     * Returns member with or without score based on score or rank
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
        $stop = isset($options['stop']) ? $options['stop'] : -1;
        $descending 	= isset($options['descending']) ? $options['descending'] : false;
        $withscores 	= isset($options['withscores']) ? $options['withscores'] : true;
        $sort_by_rank 	= isset($options['sort_by_rank']) ? $options['sort_by_rank'] : true;

        if ( ! is_int($start) or
             ! is_int($stop) or
             ! is_bool($descending) or
             ! is_bool($withscores) or
             ! is_bool($sort_by_rank)){
            throw new DatatypeInvalidArgumentException('Invalid argument');
        }

        $method =  $descending ? 'zrevrange' : 'zrange';
        $method .=  $sort_by_rank ? '' : 'byscore';

        if ($withscores == true){
            return $this->redis_client->$method($this->key_name, $start, $stop, 'withscores');
        }

        return $this->redis_client->$method($this->key_name, $start, $stop);
    }

    /**
     * Deletes key, one or more members from sorted set
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

        return call_user_func_array(array($this->redis_client, 'zrem'), $arguments);
    }

    /**
     * Returns number of members in sorted set
     * @return int
     */
    public function getCount()
    {
        return $this->redis_client->zcard($this->key_name);
    }
}