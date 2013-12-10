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
use Komita\SiderBundle\Redis\Exception\KeyDoesNotExistsException;

/**
 * Controller for key related actions
 *
 * Class KeyController
 * @package Komita\SiderBundle\Controller
 */
class KeyController extends CoreController
{
    /**
     * Displays all redis keys in pagination
     *
     * @param $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function browseAction($page)
    {
        $keys = array();
        if (! empty($_GET)){
            isset($_GET['search_keys']['database']) and $_GET['search_keys']['database'] == '' and $_GET['search_keys']['database'] = 0;
            $keys = $this->getSearchKeysResult();
        }

        return $this->getPreparedBrowsingData($keys, $page);
    }

    /**
     * Displays all information about some key
     *
     * @param string $encoded_key_name
     * @param string $database
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayKeyInfoAction($encoded_key_name, $database)
    {
        $this->addResponseData('key', $this->getRedisKeyCrudObject(urldecode($encoded_key_name), $database));
        return $this->render('KomitaSiderBundle:Key:display.key.info.html.twig', $this->response_data);
    }

    /**
     * Returns prepared keys data for browsing
     * @param $keys
     * @param $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function getPreparedBrowsingData($keys, $page)
    {
        $limit = $this->container->getParameter('komita_sider_bundle_paginator_per_page');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->filterKeys($keys, $page),
            $page,
            $limit
        );

        $this->addResponseData('pagination', $pagination);
        return $this->render('KomitaSiderBundle:Key:browse.html.twig', $this->response_data);
    }

    /**
     * Pagination objects are used in templates
     * Values on index (key name) that are in display range are replaced with objects that contains
     *
     * @param array $all_keys
     * @param $page
     *
     * @return mixed
     */
    private function filterKeys(array $all_keys, $page)
    {
        $data = $this->search_form->getData();
        $limit = $this->container->getParameter('komita_sider_bundle_paginator_per_page');
        $total_keys  = count($all_keys);
        $start_index = ($page - 1) * $limit;
        $end_index   = $limit * $page;
        $end_index > $total_keys - 1 and $end_index = $total_keys - 1;

        for ($i = $start_index; $i <= $end_index; $i ++){
            $all_keys[$i] = $this->getRedisKeyCrudObject($all_keys[$i], $data['database']);
        }

        return $all_keys;
    }

    /**
     * Returns array of keys that meets search criteria
     *
     * @return array
     */
    private function getSearchKeysResult()
    {
        $redis_client = $this->getRedisClient();
        $data = $this->search_form->getData();
        $redis_client->select($data['database']);
        $keys = (isset($data['key']) and $data['key'] != '') ? $redis_client->keys('*'.$data['key'].'*') : $redis_client->keys('*');

        if (isset($data['datatype']) and $data['datatype'] != ''){
            foreach($keys as $index => $key_name){
                try{
                    $key = $this->getRedisKeyCrudObject($key_name, $data['database']);
                    if ($key->getDatatype() != $data['datatype']){
                        unset($keys[$index]);
                    }
                }catch (KeyDoesNotExistsException $e){
                    unset($keys[$index]);
                }
            }
        }

        return array_values($keys);
    }
}
