    <?php

namespace Komita\SiderBundle\Tests\Redis;

use Komita\SiderBundle\Redis\Factory;
use Komita\SiderBundle\Tests\SiderCoreTest;

class DatatypeAbstractTest extends SiderCoreTest
{
    protected $sut;
    public function setUp()
    {
        parent::setUp();
        $this->setTestStringKey();
        $this->sut = $this->getMockForAbstractClass('Komita\SiderBundle\Redis\DatatypeAbstract',
                                                    array($this->test_data['string']['key'], $this->redis_client, REDIS_SERVER_DBNUM));
    }

    public function testGetName()
    {
        $this->assertEquals($this->test_data['string']['key'], $this->sut->getName());
    }

    public function testGetDatabase()
    {
        $this->assertEquals(REDIS_SERVER_DBNUM, $this->sut->getDatabase());
    }

    public function testGetDatatype()
    {
        $this->assertEquals('string', $this->sut->getDatatype());
    }

    public function testGetTtl()
    {
        $this->assertEquals(-1, $this->sut->getTtl());
    }
}
