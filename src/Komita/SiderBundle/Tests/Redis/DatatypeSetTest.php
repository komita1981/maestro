<?php

namespace Komita\SiderBundle\Tests\Redis;

use Komita\SiderBundle\Redis\Factory;
use Komita\SiderBundle\Tests\SiderCoreTest;

class DatatypeSetTest extends SiderCoreTest
{
    public function setUp()
    {
        parent::setUp();
        $this->setTestSetKey();
    }

    public function testSetValueNewMember()
    {
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['set']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue('setMember4');
        $this->assertEquals(count($this->test_data['set']['value']) + 1 , $keyCruder->getCount());
    }

    public function testSetValueNewMembers()
    {
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['set']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue(array('setMember4', 'setMember5'));
        $this->assertEquals(count($this->test_data['set']['value']) + 2 , $keyCruder->getCount());
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testSetValueEmptyInput()
    {
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['set']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue();
    }

    public function testGetValueAll()
    {
        $keyCruder      = Factory::forgeDatatypeCruder($this->test_data['set']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $this->assertSameValues($this->test_data['set']['value'], $keyCruder->getValue());
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\KeyDoesNotExistsException
     */
    public function testDeleteKey()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['set']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->delete();
        Factory::forgeDatatypeCruder($this->test_data['set']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
    }

    public function testDeleteMember()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['set']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->delete('setMember1');
        $this->assertEquals(count($this->test_data['set']['value']) - 1, $keyCruder->getCount());
    }

    public function testDeleteMembers()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['set']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->delete(array('setMember1', 'setMember2'));
        $this->assertEquals(count($this->test_data['set']['value']) - 2, $keyCruder->getCount());
    }


    public function testCount()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['set']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $this->assertEquals(count($this->test_data['set']['value']), $keyCruder->getCount());
    }
}
