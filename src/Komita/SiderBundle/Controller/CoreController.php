<?php

/*
* This file is part of the Sider package.
*
* (c) Milan Popovic - <komita1981@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Komita\SiderBundle\Controller;

use Komita\SiderBundle\Redis\Exception\DatatypeException;
use Komita\SiderBundle\Redis\Factory;
use Komita\SiderBundle\Redis\KeyDoesNotExistsException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Core controller for controllers of this bundle
 * It should be inherited by all other controllers that requires work with Redis client
 *
 * Class CoreController
 * @package Komita\SiderBundle\Controller
 */
class CoreController extends Controller
{
    /**
     * Search form instance
     *
     * @var \Komita\SiderBundle\Form\Search
     */
    protected $search_form;

    /**
     * Data for response
     *
     * @var
     */
    protected $response_data = array();

    /**
     * Sets search form
     *
     * @param $search_form
     *
     * @return $this
     */
    public function setSearchForm($search_form)
    {
        $this->search_form = $search_form;

        return $this;
    }

    /**
     * Adds element to response data
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function addResponseData($key, $value)
    {
        $this->response_data[$key] = $value;

        return true;
    }

    /**
     * Returns redis client
     *
     * @throws \Exception
     *
     * @return Registry
     */
    protected function getRedisClient()
    {
        $aliases = $this->container->getParameter('komita_sider_bundle_clients');

        foreach($aliases as $alias)
        {
            return $this->get($alias['alias']);
        }

        throw $this->createNotFoundException('Redis client is not defined. Check your configuration.');
    }

    /**
     * Returns key object for crud operations
     *
     * @param string $key
     * @param int $database
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return Key
     */
    protected function getRedisKeyCrudObject($key, $database)
    {
        try{
            return Factory::forgeDatatypeCruder($key, $this->getRedisClient(), $database);
        }catch (KeyDoesNotExistsException $e){
            throw $this->createNotFoundException('The key does not exists');
        }catch (DatatypeException $e){
            throw $this->createNotFoundException($e->getMessage());
        }
    }
}
