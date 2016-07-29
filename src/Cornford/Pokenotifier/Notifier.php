<?php namespace Cornford\Pokenotifier;

use Cornford\Pokenotifier\Exceptions\NotifierException;
use Cornford\Pokenotifier\Models\Position;
use Cornford\Pokenotifier\Contracts\NotifyingInterface;
use Exception;

class Notifier extends NotifierBase implements NotifyingInterface {

    const TYPE_POKEMON = 'pokemon';

    /**
     * Process scan request.
     *
     * @param Position $position
     *
     * @throws NotifierException
     *
     * @return boolean
     */
    public function processScanRequest(Position $position)
    {
        $response = $this->requestPokemonGoMapScan($position);

        if (!$response) {
            throw new NotifierException('Unable to request a current location scan from PokemonGo-Map.');
        }

        $results = $this->requestPokemonGoMapData();

        if (!$results || !isset($results['pokemons'])) {
            throw new NotifierException('Unable to request pokemon from PokemonGo-Map.');
        }

        $this->expireCachedItems();

        return $this->processPokemon($results['pokemons'], $position);
    }

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
    public function processWebhookRequest(array $result, Position $position)
    {
        if (empty($result) || !isset($result['type']) || $result['type'] != self::TYPE_POKEMON) {
            throw new NotifierException('Unable to process Pokemon results array.');
        }

        $this->expireCachedItems();

        $result = (array) $result['message'];
        $pokemonConfiguration = $this->getPokemonConfiguration();
        $result['pokemon_name'] = $pokemonConfiguration[$result['pokemon_id']]['name'];

        return $this->processPokemon([$result], $position);
    }

    /**
     * Request PokemonGo-Map scan.
     *
     * @param Position $position
     *
     * @return boolean
     */
    public function requestPokemonGoMapScan(Position $position)
    {
        try {
            $response = $this->getGuzzleClient()
                ->post(
                    sprintf('/next_loc?lat=%s&lon=%s', $position->getLatitude(), $position->getLongitude()),
                    [
                        'headers' => [
                            'accept' => 'application/hal+json',
                            'cache-control' => 'no-cache',
                        ]
                    ]
                )
                ->getBody();
        } catch (Exception $exception) {
            return false;
        }

        return ($response->__toString() === 'ok' ? true : false);
    }

    /**
     * Request PokemonGo-Map data array.
     *
     * @return array|boolean
     */
    public function requestPokemonGoMapData()
    {
        try {
            $response = $this->getGuzzleClient()
                ->get('/raw_data')
                ->json();
        } catch (Exception $exception) {
            return false;
        }

        return $response;
    }

    /**
     * Expire cache items.
     *
     * @param string $timeout
     *
     * @return boolean
     */
    public function expireCachedItems($timeout = '-1 hour')
    {
        try {
            foreach ($this->getDirectoryIterator() as $file) {
                if ($file->isDot() || $file->getFilename() == '.gitkeep') {
                    continue;
                }

                if ($file->getMTime() <= strtotime($timeout)) {
                    unlink($file->getPathname());
                }
            }
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * Send slack notification.
     *
     * @param array    $pokemon
     * @param Position $position
     *
     * @return boolean
     */
    public function sendSlackNotification(array $pokemon, Position $position)
    {
        try {
            $applicationConfiguration = $this->getApplicationConfiguration();

            if (count(array_diff(['pokemon_id', 'pokemon_name', 'latitude', 'longitude', 'disappear_time'], array_keys($pokemon))) > 0) {
                return false;
            }

            $now = (integer) (time() . round(microtime() * 1000));
            $pokemonName = '<http://pogobase.net/' . $pokemon['pokemon_id'] . '|' . $pokemon['pokemon_name'] . '>';
            $pokemonPosition = new Position($pokemon['latitude'], $pokemon['longitude']);
            $pokemonDistance = $position->calculateDistance($pokemonPosition) . 'm';
            $time = round(abs(($now - $pokemon['disappear_time']) / 1000 / 60), 0);
            $pokemonTime = (integer) ($time > 100 ? 'now' : 'in ' . $time . ' min');
            $pokemonMap = '<https://www.google.co.uk/maps/dir/' . $position->getLatitude() . ',' . $position->getLongitude() . '/' . $pokemonPosition->getLatitude() . ',' . $pokemonPosition->getLongitude() . '/?dirflg=w|location>! :world_map:';

            $this->getSlackClient()
                ->to($applicationConfiguration['slack-channel'])
                ->send($pokemonName . ' ' . $pokemonDistance . ' away. Expires ' . $pokemonTime . ' at ' . $pokemonMap);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * Process an array of Pokemon.
     *
     * @param array    $array
     * @param Position $position
     * @param boolean  $notification
     *
     * @return boolean
     */
    public function processPokemon(array $array, Position $position, $notification = true)
    {
        try {
            $applicationConfiguration = $this->getApplicationConfiguration();
            $pokemonConfiguration = $this->getPokemonConfiguration();

            foreach ($array as $pokemon) {
                if ((integer) $pokemonConfiguration[$pokemon['pokemon_id']]['rarity'] >= $applicationConfiguration['pokemon-rarity']) {
                    $cache = $applicationConfiguration['cache-directory'] . md5($pokemon['pokemon_id'] . $pokemon['encounter_id'] . $pokemon['spawnpoint_id'] . $pokemon['disappear_time']) . '.md5';

                    if (!file_exists($cache)) {
                        if ($notification) {
                            $this->sendSlackNotification($pokemon, $position);
                        }

                        file_put_contents($cache, $pokemon['encounter_id']);
                    }
                }
            }
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }
    
}