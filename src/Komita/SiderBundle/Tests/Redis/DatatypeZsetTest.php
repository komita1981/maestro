<?php

namespace Komita\SiderBundle\Tests\Redis;

use Komita\SiderBundle\Redis\Factory;
use Komita\SiderBundle\Tests\SiderCoreTest;

class DatatypeZsetTest extends SiderCoreTest
{
    public function setUp()
    {
        parent::setUp();
        $this->setTestZsetKey();
    }

    public function testSetValueNewMember()
    {
        $data['zsetMember4'] = 4;
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue($data);
        $this->assertEquals(count($this->test_data['zset']['value']) + 1 , $keyCruder->getCount());
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testSetValueInvalidScore()
    {
        $data['zsetMember4'] = 'invalid';
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue($data);
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testSetValueEmptyInput()
    {
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue();
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testSetValueInvalidInput()
    {
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->setValue('invalid input');
    }

    public function testGetValueByRankWithScoresAscending()
    {
        $options['withscores'] = true;
        $options['descending'] = false;
        $options['sort_by_rank'] = true;
        $options['start'] = 0;
        $options['stop'] = 1;
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $expected_value        = array(array('zsetMember1', 1),
            array('zsetMember2', 2));
        $this->assertEquals($expected_value, $keyCruder->getValue($options));
    }

    public function testGetValueByRankWithoutScoresAscending()
    {
        $options['withscores'] = false;
        $options['descending'] = false;
        $options['sort_by_rank'] = true;
        $options['start'] = 0;
        $options['stop'] = 1;
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $expected_value        = array('zsetMember1',
            'zsetMember2');
        $this->assertEquals($expected_value, $keyCruder->getValue($options));
    }

    public function testGetValueByRankWithScoresDescending()
    {
        $options['withscores'] = true;
        $options['descending'] = true;
        $options['sort_by_rank'] = true;
        $options['start'] = 0;
        $options['stop'] = 1;
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $expected_value        = array(array('zsetMember3', 3),
            array('zsetMember2', 2));
        $this->assertEquals($expected_value, $keyCruder->getValue($options));
    }

    public function testGetValueByRankWithoutScoresDescending()
    {
        $options['withscores'] = false;
        $options['descending'] = true;
        $options['sort_by_rank'] = true;
        $options['start'] = 0;
        $options['stop'] = 1;
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $expected_value        = array('zsetMember3',
            'zsetMember2');
        $this->assertEquals($expected_value, $keyCruder->getValue($options));
    }

    public function testGetValueByScoreWithScoresAscending()
    {
        $options['withscores'] = true;
        $options['descending'] = false;
        $options['sort_by_rank'] = false;
        $options['start'] = 1;
        $options['stop'] = 2;
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $expected_value        = array(array('zsetMember1', 1),
            array('zsetMember2', 2));
        $this->assertEquals($expected_value, $keyCruder->getValue($options));
    }

    public function testGetValueByScoreWithoutScoresAscending()
    {
        $options['withscores'] = false;
        $options['descending'] = false;
        $options['sort_by_rank'] = false;
        $options['start'] = 1;
        $options['stop'] = 2;
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $expected_value        = array('zsetMember1',
            'zsetMember2');
        $this->assertEquals($expected_value, $keyCruder->getValue($options));
    }

    public function testGetValueByScoreWithScoresDescending()
    {
        $options['withscores'] = true;
        $options['descending'] = true;
        $options['sort_by_rank'] = false;
        $options['start'] = 2;
        $options['stop'] = 1;
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $expected_value        = array(array('zsetMember2', 2),
            array('zsetMember1', 1));
        $this->assertEquals($expected_value, $keyCruder->getValue($options));
    }

    public function testGetValueByScoreWithoutScoresDescending()
    {
        $options['withscores'] = false;
        $options['descending'] = true;
        $options['sort_by_rank'] = false;
        $options['start'] = 2;
        $options['stop'] = 1;
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $expected_value        = array('zsetMember2',
            'zsetMember1');

        $this->assertEquals($expected_value, $keyCruder->getValue($options));
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testGetValueInvalidStartRangeParam()
    {
        $options['start'] = 'invalid';
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->getValue($options);
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testGetValueInvalidStopRangeParam()
    {
        $options['stop'] = 'invalid';
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->getValue($options);
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testGetValueInvalidOrderParam()
    {
        $options['descending'] = 'invalid';
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->getValue($options);
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\DatatypeInvalidArgumentException
     */
    public function testGetValueInvalidWithscoresParams()
    {
        $options['withscores'] = 'invalid';
        $keyCruder             = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->getValue($options);
    }

    /**
     * @expectedException \Komita\SiderBundle\Redis\Exception\KeyDoesNotExistsException
     */
    public function testDeleteKey()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->delete();
        Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
    }

    public function testDeleteMember()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->delete('zsetMember1');
        $this->assertEquals(count($this->test_data['zset']['value']) -1 , $keyCruder->getCount());
    }

    public function testDeleteMembers()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $keyCruder->delete(array('zsetMember1',
                                  'zsetMember2',

        ));
        $this->assertEquals(count($this->test_data['zset']['value']) -2 , $keyCruder->getCount());
    }

    public function testCount()
    {
        $keyCruder = Factory::forgeDatatypeCruder($this->test_data['zset']['key'], $this->redis_client, REDIS_SERVER_DBNUM);
        $this->assertEquals(count($this->test_data['zset']['value']), $keyCruder->getCount());
    }
}
