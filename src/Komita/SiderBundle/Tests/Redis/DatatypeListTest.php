<?php

namespace Komita\SiderBundle\Tests\Redis;

use Komita\SiderBundle\Redis\Factory;
use Komita\SiderBundle\Tests\SiderCoreTest;

class DatatypeListTest extends SiderCoreTest
{
    public function setUp()
    {
        parent::setUp();
        $this->setTestListKey();
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testSetValueWithoutData()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue();
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testSetValueNotArray()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue('NotArray');
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testSetValueInvalidHeadTailParameters()
    {
        $options['head'] = false;
        $options['tail'] = false;
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue($options);
    }

    public function testSetValueOnTail()
    {
        $options['tail'] = true;
        $options['value'] = 'listValue4';
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue($options);

        $expected_value = $this->test_data['list']['value'];
        $expected_value[] = $options['value'];

        $this->assertEquals($expected_value, $keyCruder->getValue());
    }

    public function testSetValueOnHead()
    {
        $options['head'] = true;
        $options['value'] = 'listValue4';
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue($options);

        $expected_value[] = $options['value'];
        $expected_value = array_merge($expected_value, $this->test_data['list']['value']);

        $this->assertEquals($expected_value, $keyCruder->getValue());
    }

    public function testSetValuesOnTail()
    {
        $options['tail'] = true;
        $options['value'] = array('listValue4', 'listValue5');
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue($options);

        $expected_value = $this->test_data['list']['value'];
        $expected_value = array_merge($expected_value, $options['value']);

        $this->assertEquals($expected_value, $keyCruder->getValue());
    }

    public function testGetValueAll()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $this->assertEquals($this->test_data['list']['value'], $keyCruder->getValue());
    }


    public function testGetValueTwoElements()
    {
        $options['start'] = 0;
        $options['end'] = 1;
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $this->assertEquals(array('listValue1', 'listValue2'), $keyCruder->getValue($options));
    }


    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testGetValueInvalidStartRangeParam()
    {
        $options['start'] = 'invalid';
        $keyCruder        = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->getValue($options);
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testGetValueInvalidStopRangeParam()
    {
        $options['end'] = 'invalid';
        $keyCruder       = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->getValue($options);
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\KeyDoesNotExistsException
     */
    public function testDeleteKey()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->delete();
        Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
    }

    public function testDeleteElementOnTail()
    {
        $options['tail'] = true;
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->delete($options);
        $this->assertEquals(array('listValue1', 'listValue2'), $keyCruder->getValue($options));
    }

    public function testDeleteElementOnHead()
    {
        $options['head'] = true;
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->delete($options);
        $this->assertEquals(array('listValue2', 'listValue3'), $keyCruder->getValue($options));
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testDeleteInvalidParameters()
    {
        $options['head'] = false;
        $options['tail'] = false;
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->delete($options);
    }


    public function testCount()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['list']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $this->assertEquals(count($this->test_data['list']['value']), $keyCruder->getCount());
    }
}
