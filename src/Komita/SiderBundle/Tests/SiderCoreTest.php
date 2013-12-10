<?php
namespace Komita\SiderBundle\Tests;

use Predis\Client;
use Predis\Profile\ServerProfile;

/**
 * Test case class helpful with Entity tests requiring the database interaction.
 * For regular entity tests it's better to extend standard \PHPUnit_Framework_TestCase instead.
 */
abstract class SiderCoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Redis cli   ent
     */
    protected $redis_client;

    protected $test_data = array(
        'string' =>
            array('key' => 'SiderTestStringKey',
                  'value' => 'Str'),
        'set' =>
            array('key' => 'SiderTestSetKey',
                  'value' => array('setMember1',
                      'setMember2',
                      'setMember3')),
        'zset' =>
            array('key' => 'SiderTestZSetKey',
                  'value' => array('zsetMember1' => 1,
                                   'zsetMember2' => 2,
                                   'zsetMember3' => 3),
            ),
        'list' =>
            array('key' => 'SiderTestListKey',
                  'value' => array('listValue1', 'listValue2', 'listValue3')
            ),
        'hash' =>
            array('key' => 'SiderTestHashKey',
                  'value' => array('field1' => 'value1',
                                   'field2' => 'value2',
                                   'field3' => 'value3'),
            ),
    );

    /**
     * @return null
     */
    public function setUp()
    {
        $this->setRedisClient();

        parent::setUp();
    }

    /**
     * @return null
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Sets Redis client
     */
    protected function setRedisClient()
    {
        $parameters = array(
            'host' => REDIS_SERVER_HOST,
            'port' => REDIS_SERVER_PORT,
        );

        $options = array(
            'profile' => ServerProfile::get(REDIS_SERVER_VERSION)
        );

        $client = new Client($parameters, $options);
        $client->connect();
        $client->select(REDIS_SERVER_DBNUM);
        $client->flushdb();

        $this->redis_client = $client;

        return true;
    }

    /**
     * Sets all redis keys for testing
     */
    protected function setTestAllKeys()
    {
        $this->setTestStringKey();
        $this->setTestSetKey();
        $this->setTestZSetKey();
        $this->setTestListKey();
        $this->setTestHashKey();
    }

    /**
     * Sets string keys for tests
     */
    protected function setTestStringKey()
    {
      $this->redis_client->set($this->test_data['string']['key'], $this->test_data['string']['value']);
    }

    /**
     * Sets set keys for tests
     */
    protected function setTestSetKey()
    {
        foreach($this->test_data['set']['value'] as $member){
            $this->redis_client->sadd($this->test_data['set']['key'], $member);
        }
    }

    /**
     * Sets zset keys for tests
     */
    protected function setTestZSetKey()
    {
        $this->redis_client->zadd($this->test_data['zset']['key'], $this->test_data['zset']['value']);
    }

    /**
     * Sets zset keys for tests
     */
    protected function setTestListKey()
    {
        $this->redis_client->rpush($this->test_data['list']['key'], $this->test_data['list']['value']);
    }

    /**
     * Sets hash keys for tests
     */
    protected function setTestHashKey()
    {
        $this->redis_client->hmset($this->test_data['hash']['key'], $this->test_data['hash']['value']);
    }

    /**
     * Asserts that two arrays have the same values, even if with different order.
     *
     * @param Array $expected Expected array.
     * @param Array $actual Actual array.
     */
    protected function assertSameValues(Array $expected, Array $actual)
    {
        $this->assertThat($expected, new ArrayHasSameValuesConstraint($actual));
    }
}