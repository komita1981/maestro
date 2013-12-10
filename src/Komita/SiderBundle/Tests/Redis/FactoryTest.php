<?php

namespace Komita\SiderBundle\Tests\Redis;

use Komita\SiderBundle\Redis\Factory;
use Komita\SiderBundle\Tests\SiderCoreTest;

class FactoryTest extends SiderCoreTest
{
    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\KeyDoesNotExistsException
     */
    public function testKeyDoesNotExists()
    {
        Factory::forgeDatatypeCruder('SiderNonExistingKey'.time(), $this->redis_client, REDIS_SERVER_DBNUM);
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatabaseException
     */
    public function testDatabaseDoesNotExists()
    {
        $this->setTestStringKey();
        Factory::forgeDatatypeCruder($this->test_data['string']['key'], $this->redis_client, 22);
    }
}
