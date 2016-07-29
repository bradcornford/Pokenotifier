<?php namespace Cornford\Pokenotifier\Contracts;

use DirectoryIterator;
use Maknz\Slack\Client as SlackClient;
use GuzzleHttp\Client as GuzzleClient;

interface NotifyingBaseInterface {

    /**
     * Constructor.
     *
     * @param GuzzleClient      $guzzleClient
     * @param SlackClient       $slackClient
     * @param DirectoryIterator $directoryIteratorInstance
     */
    public function __construct(
        GuzzleClient $guzzleClient = null,
        SlackClient $slackClient = null,
        DirectoryIterator $directoryIteratorInstance = null
    );

    /**
     * Load Application configuration array.
     *
     * @return void
     */
    public function loadApplicationConfiguration();

    /**
     * Get Application configuration array.
     *
     * @return array
     */
    public function getApplicationConfiguration();

    /**
     * Get Pokemon configuration array.
     *
     * @return array
     */
    public function loadPokemonConfiguration();

    /**
     * Get Pokemon configuration array.
     *
     * @return array
     */
    public function getPokemonConfiguration();

    /**
     * Create Guzzle client.
     *
     * @return void
     */
    public function createGuzzleClient();

    /**
     * Get Guzzle client.
     *
     * @return GuzzleClient
     */
    public function getGuzzleClient();

    /**
     * Set Guzzle client.
     *
     * @param GuzzleClient $client
     *
     * @return void
     */
    public function setGuzzleClient(GuzzleClient $client);

    /**
     * Create Slack client.
     *
     * @return void
     */
    public function createSlackClient();

    /**
     * Get Slack client.
     *
     * @return SlackClient
     */
    public function getSlackClient();

    /**
     * Set Slack client.
     *
     * @param SlackClient $client
     *
     * @return void
     */
    public function setSlackClient(SlackClient $client);

    /**
     * Create Directory Iterator.
     *
     * @return DirectoryIterator
     */
    public function createDirectoryIterator();

    /**
     * Get Directory Iterator.
     *
     * @return DirectoryIterator
     */
    public function getDirectoryIterator();

    /**
     * Set Directory Iterator.
     *
     * @param DirectoryIterator $class
     *
     * @return void
     */
    public function setDirectoryIterator(DirectoryIterator $class);

}
