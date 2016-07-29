<?php namespace Cornford\Pokenotifier;

use Cornford\Pokenotifier\Contracts\NotifyingBaseInterface;
use Cornford\Pokenotifier\Exceptions\NotifierException;
use DirectoryIterator;
use GuzzleHttp\Client as GuzzleClient;
use Maknz\Slack\Client as SlackClient;

abstract class NotifierBase implements NotifyingBaseInterface
{
    const REQUEST_TIMEOUT = 120;

    /**
     * Slack Client.
     *
     * @var SlackClient
     */
    private $slackClient;

    /**
     * Guzzle Client.
     *
     * @var GuzzleClient
     */
    private $guzzleClient;

    /**
     * Directory Iterator.
     *
     * @var DirectoryIterator
     */
    private $directoryIterator;

    /**
     * Application configuration.
     *
     * @var array
     */
    private $applicationConfiguration;

    /**
     * Pokemon configuration.
     *
     * @var array
     */
    private $pokemonConfiguration;

    /**
     * Constructor.
     *
     * @param GuzzleClient      $guzzleClient
     * @param SlackClient       $slackClient
     * @param DirectoryIterator $directoryIterator
     */
    public function __construct(
        GuzzleClient $guzzleClient = null,
        SlackClient $slackClient = null,
        DirectoryIterator $directoryIterator = null
    ) {
        $this->loadApplicationConfiguration();
        $this->loadPokemonConfiguration();

        if ($guzzleClient === null) {
            $this->createGuzzleClient();
        } else {
            $this->setGuzzleClient($guzzleClient);
        }

        if ($slackClient === null) {
            $this->createSlackClient();
        } else {
            $this->setSlackClient($slackClient);
        }

        if ($directoryIterator === null) {
            $this->createDirectoryIterator();
        } else {
            $this->setDirectoryIterator($directoryIterator);
        }
    }

    /**
     * Load Application configuration array.
     *
     * @return void
     */
    public function loadApplicationConfiguration()
    {
        $this->applicationConfiguration = $this->getConfigurationFile( __DIR__ . '/../../config/application.php');
    }

    /**
     * Get Application configuration array.
     *
     * @return array
     */
    public function getApplicationConfiguration()
    {
        return $this->applicationConfiguration;
    }

    /**
     * Get Pokemon configuration array.
     *
     * @return array
     */
    public function loadPokemonConfiguration()
    {
        $this->pokemonConfiguration = $this->getConfigurationFile(__DIR__ . '/../../config/pokemon.php');
    }

    /**
     * Get Pokemon configuration array.
     *
     * @return array
     */
    public function getPokemonConfiguration()
    {
        return $this->pokemonConfiguration;
    }

    /**
     * Get configuration file.
     *
     * @param string $filepath
     *
     * @throws NotifierException
     *
     * @return array
     */
    private function getConfigurationFile($filepath)
    {
        if (!is_file($filepath)) {
            throw new NotifierException('Unable to locate configuration file: ' . $filepath);
        }

        return include $filepath;
    }

    /**
     * Create Guzzle client.
     *
     * @return void
     */
    public function createGuzzleClient()
    {
        $configuration = $this->getApplicationConfiguration();

        $this->setGuzzleClient(
            new GuzzleClient([
                'base_url' => sprintf('%s://%s:%s', $configuration['pokemongo-protocol'], $configuration['pokemongo-ip'], $configuration['pokemongo-port']),
                'defaults' => [
                    'connect_timeout' => self::REQUEST_TIMEOUT,
                    'timeout' => self::REQUEST_TIMEOUT,
                ]
            ])
        );
    }

    /**
     * Get Guzzle client.
     *
     * @return GuzzleClient
     */
    public function getGuzzleClient()
    {
        return $this->guzzleClient;
    }

    /**
     * Set Guzzle client.
     *
     * @param GuzzleClient $class
     *
     * @return void
     */
    public function setGuzzleClient(GuzzleClient $class)
    {
        $this->guzzleClient = $class;
    }

    /**
     * Create Slack client.
     *
     * @return void
     */
    public function createSlackClient()
    {
        $configuration = $this->getApplicationConfiguration();

        $this->setSlackClient(new SlackClient($configuration['slack-webhook'], [], $this->getGuzzleClient()));
    }

    /**
     * Get Slack client.
     *
     * @return SlackClient
     */
    public function getSlackClient()
    {
        return $this->slackClient;
    }

    /**
     * Set Slack client.
     *
     * @param SlackClient $class
     *
     * @return void
     */
    public function setSlackClient(SlackClient $class)
    {
        $this->slackClient = $class;
    }

    /**
     * Create Directory Iterator.
     *
     * @return DirectoryIterator
     */
    public function createDirectoryIterator()
    {
        $configuration = $this->getApplicationConfiguration();

        $this->setDirectoryIterator(new DirectoryIterator($configuration['cache-directory']));
    }

    /**
     * Get Directory Iterator.
     *
     * @return DirectoryIterator
     */
    public function getDirectoryIterator()
    {
        return $this->directoryIterator;
    }

    /**
     * Set Directory Iterator.
     *
     * @param DirectoryIterator $class
     *
     * @return void
     */
    public function setDirectoryIterator(DirectoryIterator $class)
    {
        $this->directoryIterator = $class;
    }

}