<?php namespace Cornford\Pokenotifier\Contracts;

use Cornford\Pokenotifier\Exceptions\NotifierException;
use Cornford\Pokenotifier\Models\Position;

interface NotifyingInterface {

    /**
     * Process scan request.
     *
     * @param Position $position
     *
     * @throws NotifierException
     *
     * @return boolean
     */
    public function processScanRequest(Position $position);

    /**
     * Process webhook request.
     *
     * @param array    $result
     * @param Position $position
     *
     * @throws NotifierException
     *
     * @return boolean
     */
    public function processWebhookRequest(array $result, Position $position);

    /**
     * Request PokemonGo-Map scan.
     *
     * @param Position $position
     *
     * @return boolean
     */
    public function requestPokemonGoMapScan(Position $position);

    /**
     * Request PokemonGo-Map data array.
     *
     * @return array|boolean
     */
    public function requestPokemonGoMapData();

    /**
     * Expire cache items.
     *
     * @return boolean
     */
    public function expireCachedItems();

    /**
     * Send slack notification.
     *
     * @param array    $pokemon
     * @param Position $position
     *
     * @return boolean
     */
    public function sendSlackNotification(array $pokemon, Position $position);

    /**
     * Process an array of Pokemon.
     *
     * @param array    $array
     * @param Position $position
     * @param boolean  $notification
     *
     * @return boolean
     */
    public function processPokemon(array $array, Position $position, $notification = true);

}
