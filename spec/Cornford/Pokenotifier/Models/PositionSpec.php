<?php namespace spec\Cornford\Pokenotifier\Models;

use Cornford\Pokenotifier\Models\Position;
use PhpSpec\ObjectBehavior;
use Mockery;

class PositionSpec extends ObjectBehavior
{

    public function let()
    {
        $this->beConstructedWith(0, 0);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Cornford\Pokenotifier\Models\Position');
    }

    public function it_can_set_and_get_a_latitude()
    {
        $this->setLatitude(1);
        $this->getLatitude()->shouldReturn(1);
    }

    public function it_can_set_and_get_a_longitude()
    {
        $this->setLongitude(1);
        $this->getLongitude()->shouldReturn(1);
    }

    public function it_should_calculate_the_distance_between_two_positions()
    {
        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(1);
        $position->shouldReceive('getLongitude')->andReturn(1);

        $this->calculateDistance($position)->shouldReturn((float) 157242);
    }

}