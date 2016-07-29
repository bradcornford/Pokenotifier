<?php namespace spec\Cornford\Pokenotifier;

use Cornford\Pokenotifier\Notifier;
use PhpSpec\ObjectBehavior;
use Mockery;

class NotifierSpec extends ObjectBehavior
{

    public function let()
    {
        $guzzleClient = Mockery::mock('GuzzleHttp\Client');
        $guzzleClient->shouldReceive('post')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('getBody')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('__toString')->andReturn('ok');
        $guzzleClient->shouldReceive('get')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('json')->andReturn(['pokemons' => []]);

        $slackClient = Mockery::mock('Maknz\Slack\Client');
        $slackClient->shouldReceive('make')->andReturn('');

        $directoryIterator = Mockery::mock('DirectoryIterator');
        $directoryIterator->shouldReceive('rewind');
        $directoryIterator->shouldReceive('valid')->andReturn(false);

        $this->beConstructedWith($guzzleClient, $slackClient, $directoryIterator);
    }
    
    public function it_is_initializable()
    {
        $this->shouldHaveType('Cornford\Pokenotifier\Notifier');
    }

    public function it_should_return_an_array_when_getting_application_configuration()
    {
        $this->getApplicationConfiguration()->shouldBeArray();
    }

    public function it_should_return_an_array_when_getting_pokemon_configuration()
    {
        $this->getPokemonConfiguration()->shouldBeArray();
    }

    public function it_should_create_a_guzzle_client()
    {
        $this->createGuzzleClient()->shouldReturn(null);
    }

    public function it_should_set_and_get_a_guzzle_client()
    {
        $guzzleClient = Mockery::mock('GuzzleHttp\Client');

        $this->setGuzzleClient($guzzleClient);
        $this->getGuzzleClient()->shouldReturn($guzzleClient);
    }

    public function it_should_create_a_slack_client()
    {
        $this->createSlackClient()->shouldReturn(null);
    }

    public function it_should_set_and_get_a_slack_client()
    {
        $slackClient = Mockery::mock('Maknz\Slack\Client');

        $this->setSlackClient($slackClient);
        $this->getSlackClient()->shouldReturn($slackClient);
    }

    public function it_should_create_a_directory_iterator()
    {
        $this->createDirectoryIterator()->shouldReturn(null);
    }

    public function it_should_set_and_get_a_directory_iterator()
    {
        $directoryIterator = Mockery::mock('DirectoryIterator');

        $this->setDirectoryIterator($directoryIterator);
        $this->getDirectoryIterator()->shouldReturn($directoryIterator);
    }

    public function it_can_process_a_scan_request()
    {
        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $this->processScanRequest($position)->shouldReturn(true);
    }

    public function it_throws_an_exception_when_a_bad_result_from_scan_request_is_received()
    {
        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');
        $guzzleClient->shouldReceive('post')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('getBody')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('__toString')->andReturn('error');

        $slackClient = Mockery::mock('Maknz\Slack\Client');

        $directoryIterator = Mockery::mock('DirectoryIterator');

        $this->beConstructedWith($guzzleClient, $slackClient, $directoryIterator);

        $this->shouldThrow('Cornford\Pokenotifier\Exceptions\NotifierException')->during('processScanRequest', [$position]);
    }

    public function it_throws_an_exception_when_a_bad_result_from_array_scan_request_is_received()
    {
        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');
        $guzzleClient->shouldReceive('post')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('getBody')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('__toString')->andReturn('ok');
        $guzzleClient->shouldReceive('get')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('json')->andReturn([]);

        $slackClient = Mockery::mock('Maknz\Slack\Client');

        $directoryIterator = Mockery::mock('DirectoryIterator');

        $this->beConstructedWith($guzzleClient, $slackClient, $directoryIterator);

        $this->shouldThrow('Cornford\Pokenotifier\Exceptions\NotifierException')->during('processScanRequest', [$position]);
    }

    public function it_can_process_a_webhook_request()
    {
        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $pokemon = [
            'pokemon_id' => '1',
            'encounter_id' => 1,
            'spawnpoint_id' => 1,
            'latitude' => 0,
            'longitude' => 0,
            'disappear_time' => time(),
        ];

        $this->processWebhookRequest(['message' => $pokemon, 'type' => 'pokemon'], $position)->shouldReturn(true);
    }

    public function it_throws_an_exception_when_a_bad_result_from_webhook_request_is_received()
    {
        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');
        $guzzleClient->shouldReceive('post')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('getBody')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('__toString')->andReturn('error');

        $slackClient = Mockery::mock('Maknz\Slack\Client');

        $directoryIterator = Mockery::mock('DirectoryIterator');

        $this->beConstructedWith($guzzleClient, $slackClient, $directoryIterator);

        $this->shouldThrow('Cornford\Pokenotifier\Exceptions\NotifierException')->during('processWebhookRequest', [[], $position]);
    }

    public function it_can_process_a_scan_request_and_return_true_on_success()
    {
        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $this->requestPokemonGoMapScan($position)->shouldReturn(true);
    }

    public function it_can_process_a_scan_request_and_return_false_on_error()
    {
        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');
        $guzzleClient->shouldReceive('post')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('getBody')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('__toString')->andReturn('error');

        $slackClient = Mockery::mock('Maknz\Slack\Client');

        $directoryIterator = Mockery::mock('DirectoryIterator');

        $this->beConstructedWith($guzzleClient, $slackClient, $directoryIterator);

        $this->requestPokemonGoMapScan($position)->shouldReturn(false);
    }

    public function it_can_process_a_data_request_and_return_an_array_on_success()
    {
        $this->requestPokemonGoMapData()->shouldReturn(['pokemons' => []]);
    }

    public function it_can_process_a_data_request_and_return_false_on_error()
    {
        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');
        $guzzleClient->shouldReceive('get')->andThrow('Exception');

        $slackClient = Mockery::mock('Maknz\Slack\Client');

        $directoryIterator = Mockery::mock('DirectoryIterator');

        $this->beConstructedWith($guzzleClient, $slackClient, $directoryIterator);

        $this->requestPokemonGoMapData()->shouldReturn(false);
    }

    public function it_can_expire_old_cached_items_and_return_true_on_success()
    {
        $file = Mockery::mock('DirectoryIterator');
        $file->shouldReceive('isDot')->andReturn(false);
        $file->shouldReceive('getMTime')->andReturn(time());

        $directoryIterator = Mockery::mock('DirectoryIterator');
        $directoryIterator->shouldReceive('rewind');
        $directoryIterator->shouldReceive('valid')->andReturn(false);

        $this->setDirectoryIterator($directoryIterator);

        $this->expireCachedItems()->shouldReturn(true);
    }

    public function it_can_expire_old_cached_items_and_return_false_on_error()
    {
        $slackClient = Mockery::mock('DirectoryIterator');
        $slackClient->shouldReceive('to')->andReturn($slackClient);
        $slackClient->shouldReceive('send')->andReturn(true);

        $file = Mockery::mock('DirectoryIterator');
        $file->shouldReceive('isDot')->andThrow('Exception');

        $directoryIterator = Mockery::mock('DirectoryIterator');
        $directoryIterator->shouldReceive('rewind');
        $directoryIterator->shouldReceive('valid')->andReturn(true);
        $directoryIterator->shouldReceive('current')->andReturn($file);

        $this->setDirectoryIterator($directoryIterator);
        $this->expireCachedItems()->shouldReturn(false);
    }

    public function it_can_send_a_slack_notification_and_return_true_on_success()
    {
        $pokemon = [
            'pokemon_id' => '',
            'pokemon_name' => '',
            'latitude' => '',
            'longitude' => '',
            'disappear_time' => '',
        ];

        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);
        $position->shouldReceive('calculateDistance')->andReturn(0);

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');

        $slackClient = Mockery::mock('Maknz\Slack\Client');
        $slackClient->shouldReceive('to')->andReturn($slackClient);
        $slackClient->shouldReceive('send')->andReturn(true);

        $directoryIterator = Mockery::mock('DirectoryIterator');

        $this->beConstructedWith($guzzleClient, $slackClient, $directoryIterator);

        $this->sendSlackNotification($pokemon, $position)->shouldReturn(true);
    }

    public function it_can_send_a_slack_notification_and_return_false_on_error()
    {
        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $slackClient = Mockery::mock('Maknz\Slack\Client');
        $slackClient->shouldReceive('to')->andThrow('Exception');

        $this->setSlackClient($slackClient);
        $this->sendSlackNotification([], $position)->shouldReturn(false);
    }

    public function it_can_process_an_array_of_pokemon_and_return_true_on_success()
    {
        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $this->processPokemon([], $position)->shouldReturn(true);
    }

    public function it_can_process_an_array_of_pokemon_and_return_false_on_error()
    {
        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');

        $slackClient = Mockery::mock('Maknz\Slack\Client');
        $slackClient->shouldReceive('to')->andThrow('Exception');

        $directoryIterator = Mockery::mock('DirectoryIterator');

        $this->beConstructedWith($guzzleClient, $slackClient, $directoryIterator);

        $this->processPokemon([[]], $position)->shouldReturn(false);
    }

}