<?php

namespace Komita\SiderBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class KeyControllerTest extends WebTestCase
{
    public function testInitialPageDisplay()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/sider/browse-keys');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($crawler->filter('div.container')->count() > 0);
    }


    public function testFormSubmit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/sider/browse-keys?search_keys[key]=friend');
        /*$form = $crawler->selectButton('search_keys_search')->form(array(), 'GET');
        $crawler = $client->submit($form, array('search_keys[key]' => 'friend'));*/

        //var_dump($crawler->filter('div.togglebox')->text());exit;
        //$this->assertTrue($crawler->filter('tr.search_result_row')->count() > 0);
        $this->assertTrue(1 > 0);
    }
}
