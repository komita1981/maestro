<?php

namespace Komita\SiderBundle\Tests\Redis;

use Komita\SiderBundle\Redis\Factory;
use Komita\SiderBundle\Tests\SiderCoreTest;

class DatatypeStringTest extends SiderCoreTest
{
    public function setUp()
    {
        parent::setUp();
        $this->setTestStringKey();
    }

    public function testSetValueUpdateKey()
    {
        $new_value = 'String';
        $keyCruder      = Factory::forgeDatatypeCruder($this->test_data['string']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue($new_value);
        $this->assertEquals($new_value, $keyCruder->getValue());
    }

    public function testGetValue()
    {
        $keyCruder      = Factory::forgeDatatypeCruder($this->test_data['string']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $this->assertEquals($this->test_data['string']['value'], $keyCruder->getValue());
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\KeyDoesNotExistsException
     */
    public function testDeleteKey()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['string']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->delete();
        Factory::forgeDatatypeCruder($this->test_data['string']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
    }


    public function testCount()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['string']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $this->assertEquals(strlen($this->test_data['string']['value']), $keyCruder->getCount());
    }
}
