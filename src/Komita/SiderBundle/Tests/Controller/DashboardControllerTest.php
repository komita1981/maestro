<?php

namespace Komita\SiderBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase
{
    public function testDisplay()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/sider');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($crawler->filter('div.container')->count() > 0);
    }
}
