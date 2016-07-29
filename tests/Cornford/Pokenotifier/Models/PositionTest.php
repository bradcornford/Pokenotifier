<?php namespace tests\Cornford\Pokenotifier\Models;

use Cornford\Pokenotifier\Models\Position;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class PositionTest extends TestCase
{

    public function testConstruct()
    {
        $position = new Position(0, 0);

        $this->assertEquals($position, new Position(0, 0));
    }

    public function testSetAndGetLongitude()
    {
        $position = new Position(0, 0);

        $position->setLongitude(1);

        $this->assertEquals($position->getLongitude(), 1);
    }

    public function testSetAndGetLatitude()
    {
        $position = new Position(0, 0);

        $position->setLatitude(1);

        $this->assertEquals($position->getLatitude(), 1);
    }

    public function testCalculateDistance()
    {
        $position = new Position(0, 0);

        $this->assertEquals($position->calculateDistance(new Position(1, 1)), (float) 157242);
    }

}