<?php

namespace Komita\SiderBundle\Tests\Redis;

use Komita\SiderBundle\Redis\Factory;
use Komita\SiderBundle\Tests\SiderCoreTest;

class DatatypeHashTest extends SiderCoreTest
{
    public function setUp()
    {
        parent::setUp();
        $this->setTestHashKey();
    }

    public function testSetValue()
    {
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['hash']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue(array('field4' => 'value4'));
        $this->assertEquals(count($this->test_data['hash']['value']) + 1 , $keyCruder->getCount());
    }

    public function testSetValues()
    {
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['hash']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue(array('field4' => 'value4', 'field5' => 'value5'));
        $this->assertEquals(count($this->test_data['hash']['value']) + 2 , $keyCruder->getCount());
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testSetInvalidValue()
    {
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['hash']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue('value');
    }

    public function testGetAllValues()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['hash']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $this->assertEquals($this->test_data['hash']['value'], $keyCruder->getValue());
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\KeyDoesNotExistsException
     */
    public function testKeyDelete()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['hash']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->delete();
        Factory::forgeDatatypeCruder($this->test_data['hash']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
    }

    public function testFieldDelete()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['hash']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->delete('field1');

        unset($this->test_data['hash']['value']['field1']);

        $this->assertEquals($this->test_data['hash']['value'], $keyCruder->getValue());
    }

    public function testFieldsDelete()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['hash']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->delete(array('field1', 'field2'));

        unset($this->test_data['hash']['value']['field1']);
        unset($this->test_data['hash']['value']['field2']);

        $this->assertEquals($this->test_data['hash']['value'], $keyCruder->getValue());
    }


    public function testCount()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['hash']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $this->assertEquals(count($this->test_data['hash']['value']), $keyCruder->getCount());
    }
}
