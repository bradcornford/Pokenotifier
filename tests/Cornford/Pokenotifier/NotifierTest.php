<?php namespace tests\Cornford\Pokenotifier;

use Cornford\Pokenotifier\Notifier;
use DirectoryIterator;
use GuzzleHttp\Client as GuzzleClient;
use Maknz\Slack\Client as SlackClient;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use Cornford\Pokenotifier\Exceptions\NotifierException;

class NotifierTest extends TestCase
{

    public function testConstruct()
    {
        $guzzleClient = Mockery::mock('GuzzleHttp\Client');

        $slackClient = Mockery::mock('Maknz\Slack\Client');

        $directoryIterator = Mockery::mock('DirectoryIterator');

        $notifier = new Notifier($guzzleClient, $slackClient, $directoryIterator);

        $this->assertEquals($notifier, new Notifier($guzzleClient, $slackClient, $directoryIterator));
    }

    public function testLoadAndGetApplicationConfiguration()
    {
        $notifier = new Notifier();
        $configuration = $notifier->getApplicationConfiguration();

        $notifier->loadApplicationConfiguration();

        $this->assertEquals($notifier->getApplicationConfiguration(), $configuration);
    }

    public function testLoadAndGetPokemonConfiguration()
    {
        $notifier = new Notifier();
        $configuration = $notifier->getPokemonConfiguration();

        $notifier->loadPokemonConfiguration();

        $this->assertEquals($notifier->getPokemonConfiguration(), $configuration);
    }

    public function testCreateGuzzleClient()
    {
        $notifier = new Notifier();
        $notifier->createGuzzleClient();

        $this->assertInstanceOf(GuzzleClient::class, $notifier->getGuzzleClient());
    }

    public function testSetAndGetGuzzleClient()
    {
        $notifier = new Notifier();
        $guzzleClient = new GuzzleClient();

        $notifier->setGuzzleClient($guzzleClient);

        $this->assertEquals($notifier->getGuzzleClient(), $guzzleClient);
    }

    public function testCreateSlackClient()
    {
        $notifier = new Notifier();
        $notifier->createSlackClient();

        $this->assertInstanceOf(SlackClient::class, $notifier->getSlackClient());
    }

    public function testSetAndGetSlackClient()
    {
        $notifier = new Notifier();
        $configuration = $notifier->getApplicationConfiguration();
        $slackClient = new SlackClient($configuration['slack-webhook'], [], $notifier->getGuzzleClient());

        $notifier->setSlackClient($slackClient);

        $this->assertEquals($notifier->getSlackClient(), $slackClient);
    }

    public function testCreateDirectoryIterator()
    {
        $notifier = new Notifier();
        $notifier->createDirectoryIterator();

        $this->assertInstanceOf(DirectoryIterator::class, $notifier->getDirectoryIterator());
    }

    public function testSetAndGetDirectoryIterator()
    {
        $notifier = new Notifier();
        $configuration = $notifier->getApplicationConfiguration();
        $directoryIterator = new DirectoryIterator($configuration['cache-directory']);

        $notifier->setDirectoryIterator($directoryIterator);

        $this->assertEquals($notifier->getDirectoryIterator(), $directoryIterator);
    }

    public function testProcessScanRequestSuccess()
    {
        $notifier = new Notifier();

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');
        $guzzleClient->shouldReceive('post')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('getBody')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('__toString')->andReturn('ok');
        $guzzleClient->shouldReceive('get')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('json')->andReturn(['pokemons' => []]);

        $notifier->setGuzzleClient($guzzleClient);

        $slackClient = Mockery::mock('Maknz\Slack\Client');
        $slackClient->shouldReceive('make')->andReturn('');

        $notifier->setSlackClient($slackClient);

        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $this->assertEquals($notifier->processScanRequest($position), true);
    }

    public function testProcessScanRequestExceptionThrownDuringRequestScan()
    {
        $notifier = new Notifier();

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');
        $guzzleClient->shouldReceive('post')->andThrow('Exception');

        $notifier->setGuzzleClient($guzzleClient);

        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $this->setExpectedException('Cornford\Pokenotifier\Exceptions\NotifierException');

        $notifier->processScanRequest($position);
    }

    public function testProcessScanRequestExceptionThrownDuringRequestData()
    {
        $notifier = new Notifier();

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');
        $guzzleClient->shouldReceive('post')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('getBody')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('__toString')->andReturn('ok');
        $guzzleClient->shouldReceive('get')->andThrow('Exception');

        $notifier->setGuzzleClient($guzzleClient);

        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $this->setExpectedException('Cornford\Pokenotifier\Exceptions\NotifierException');

        $notifier->processScanRequest($position);
    }

    public function testProcessWebhookRequestSuccess()
    {
        $notifier = new Notifier();

        $slackClient = Mockery::mock('Maknz\Slack\Client');
        $slackClient->shouldReceive('to')->andReturn($slackClient);
        $slackClient->shouldReceive('send')->andReturn(true);

        $notifier->setSlackClient($slackClient);

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

        $this->assertEquals($notifier->processWebhookRequest(['message' => $pokemon, 'type' => 'pokemon'], $position), true);
    }

    public function testProcessWebhookRequestExceptionThrownWithInvalidResults()
    {
        $notifier = new Notifier();

        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $this->setExpectedException('Cornford\Pokenotifier\Exceptions\NotifierException');

        $this->assertEquals($notifier->processWebhookRequest([], $position), true);
    }

    public function testRequestPokemonGoMapScanSuccess()
    {
        $notifier = new Notifier();

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');
        $guzzleClient->shouldReceive('post')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('getBody')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('__toString')->andReturn('ok');

        $notifier->setGuzzleClient($guzzleClient);

        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $this->assertEquals($notifier->requestPokemonGoMapScan($position), true);
    }

    public function testRequestPokemonGoMapScanFailure()
    {
        $notifier = new Notifier();

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');
        $guzzleClient->shouldReceive('post')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('getBody')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('__toString')->andReturn('');

        $notifier->setGuzzleClient($guzzleClient);

        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $this->assertEquals($notifier->requestPokemonGoMapScan($position), false);
    }

    public function testRequestPokemonGoMapScanExceptionThrownDuringRequest()
    {
        $notifier = new Notifier();

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');
        $guzzleClient->shouldReceive('post')->andThrow('Exception');

        $notifier->setGuzzleClient($guzzleClient);

        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $this->assertEquals($notifier->requestPokemonGoMapScan($position), false);
    }

    public function testRequestPokemonGoMapDataSuccess()
    {
        $notifier = new Notifier();

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');
        $guzzleClient->shouldReceive('get')->andReturn($guzzleClient);
        $guzzleClient->shouldReceive('json')->andReturn([]);

        $notifier->setGuzzleClient($guzzleClient);

        $this->assertEquals($notifier->requestPokemonGoMapData(), []);
    }

    public function testRequestPokemonGoMapDataFailure()
    {
        $notifier = new Notifier();

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');
        $guzzleClient->shouldReceive('get')->andThrow('Exception');

        $notifier->setGuzzleClient($guzzleClient);

        $this->assertEquals($notifier->requestPokemonGoMapData(), false);
    }

    public function testExpireCachedItemsSuccess()
    {
        $notifier = new Notifier();

        $this->assertEquals($notifier->expireCachedItems(), true);
    }

    public function testExpireCachedItemsFailure()
    {
        $notifier = new Notifier();

        $file = Mockery::mock('DirectoryIterator');
        $file->shouldReceive('isDot')->andThrow('Exception');

        $directoryIterator = Mockery::mock('DirectoryIterator');
        $directoryIterator->shouldReceive('rewind');
        $directoryIterator->shouldReceive('valid')->andReturn(true);
        $directoryIterator->shouldReceive('current')->andReturn($file);

        $notifier->setDirectoryIterator($directoryIterator);

        $this->assertEquals($notifier->expireCachedItems(), false);
    }

    public function testSendSlackNotificationSuccess()
    {
        $notifier = new Notifier();

        $slackClient = Mockery::mock('Maknz\Slack\Client');
        $slackClient->shouldReceive('to')->andReturn($slackClient);
        $slackClient->shouldReceive('send')->andReturn(true);

        $notifier->setSlackClient($slackClient);

        $directoryIterator = Mockery::mock('DirectoryIterator');

        $notifier->setDirectoryIterator($directoryIterator);

        $pokemon = [
            'pokemon_id' => '1',
            'pokemon_name' => 'Bulbasaur',
            'encounter_id' => 1,
            'spawnpoint_id' => 1,
            'latitude' => 0,
            'longitude' => 0,
            'disappear_time' => time(),
        ];

        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);
        $position->shouldReceive('calculateDistance')->andReturn(0);

        $this->assertEquals($notifier->sendSlackNotification($pokemon, $position), true);
    }

    public function testSendSlackNotificationFailureFromData()
    {
        $notifier = new Notifier();

        $slackClient = Mockery::mock('Maknz\Slack\Client');
        $slackClient->shouldReceive('to')->andReturn($slackClient);
        $slackClient->shouldReceive('send')->andReturn(true);

        $notifier->setSlackClient($slackClient);

        $directoryIterator = Mockery::mock('DirectoryIterator');

        $notifier->setDirectoryIterator($directoryIterator);

        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);
        $position->shouldReceive('calculateDistance')->andReturn(0);

        $this->assertEquals($notifier->sendSlackNotification([], $position), false);
    }

    public function testSendSlackNotificationFailureOnException()
    {
        $notifier = new Notifier();

        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');

        $notifier->setGuzzleClient($guzzleClient);

        $slackClient = Mockery::mock('Maknz\Slack\Client');
        $slackClient->shouldReceive('to')->andThrow('Exception');

        $notifier->setSlackClient($slackClient);

        $this->assertEquals($notifier->sendSlackNotification([], $position), false);
    }

    public function testProcessPokemonSuccess()
    {
        $notifier = new Notifier();

        $slackClient = Mockery::mock('Maknz\Slack\Client');
        $slackClient->shouldReceive('to')->andReturn($slackClient);
        $slackClient->shouldReceive('send')->andReturn(true);

        $notifier->setSlackClient($slackClient);

        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $pokemon = [
            'pokemon_id' => 1,
            'pokemon_name' => 'Bulbasaur',
            'encounter_id' => 1,
            'spawnpoint_id' => 1,
            'latitude' => 0,
            'longitude' => 0,
            'disappear_time' => time(),
        ];

        $this->assertEquals($notifier->processPokemon([$pokemon], $position), true);
    }

    public function testProcessPokemonFailure()
    {
        $notifier = new Notifier();

        $position = Mockery::mock('Cornford\Pokenotifier\Models\Position');
        $position->shouldReceive('getLatitude')->andReturn(0);
        $position->shouldReceive('getLongitude')->andReturn(0);

        $guzzleClient = Mockery::mock('GuzzleHttp\Client');

        $notifier->setGuzzleClient($guzzleClient);

        $slackClient = Mockery::mock('Maknz\Slack\Client');
        $slackClient->shouldReceive('to')->andThrow('Exception');

        $notifier->setSlackClient($slackClient);

        $directoryIterator = Mockery::mock('DirectoryIterator');

        $notifier->setDirectoryIterator($directoryIterator);

        $this->assertEquals($notifier->processPokemon([[]], $position), false);
    }

}