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
use Komita\SiderBundle\Redis\Exception\DatabaseException;
use Komita\SiderBundle\Redis\Exception\Datatype;
use Komita\SiderBundle\Redis\Exception\DatatypeException;
use Komita\SiderBundle\Redis\Exception\KeyDoesNotExistsException;
use Exception;

/**
 * Factory for datatype instances
 *
 * Class Factory
 * @package Komita\SiderBundle\Redis
 */
class Factory
{
    /**
     * Returns instance of Redis datatype
     *
     * @param $key_name
     * @param $redis_client
     * @param $database
     *
     * @throws DatatypeException
     * @throws KeyDoesNotExistsException
     * @throws DatabaseException
     *
     * @return mixed
     */
    public static function forgeDatatypeCruder($key_name, $redis_client, $database){
        try{
            $redis_client->select($database);
        }catch (Exception $e){
            throw new DatabaseException('Database '.$database.' does not exists');
        }

        if (! $redis_client->exists($key_name)){
            throw new KeyDoesNotExistsException('Key '.$key_name.' does not exists in database '.$database);
        }

        $type = $redis_client->type($key_name);

        $class_name = '\\Komita\\SiderBundle\\Redis\Datatype'.ucfirst($type);

        if (! class_exists($class_name)){
            throw new DatatypeException('Class is not defined for type '.$type);
        }

        return new $class_name($key_name, $redis_client, $database);
    }
}