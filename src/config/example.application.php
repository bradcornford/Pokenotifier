<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Default Latitude
    |--------------------------------------------------------------------------
    |
    | Default latitude used for scan.
    |
    */
    'default-latitude' => (isset($_ENV['DEFAULT_LATITUDE']) ? $_ENV['DEFAULT_LATITUDE'] : 0),

    /*
    |--------------------------------------------------------------------------
    | Default Longitude
    |--------------------------------------------------------------------------
    |
    | Default longitude used for scan.
    |
    */
    'default-longitude' => (isset($_ENV['DEFAULT_LONGITUDE']) ? $_ENV['DEFAULT_LONGITUDE'] : 0),

    /*
    |--------------------------------------------------------------------------
    | Cache Directory
    |--------------------------------------------------------------------------
    |
    | Cache directory location.
    |
    */
    'cache-directory' => (isset($_ENV['CACHE-DIRECTORY']) ? $_ENV['CACHE-DIRECTORY'] : __DIR__ . '/../../cache/'),

    /*
    |--------------------------------------------------------------------------
    | PokemonGo-Map Server Protocol
    |--------------------------------------------------------------------------
    |
    | The protocol of the PokemonGo-Map server.
    |
    */
    'pokemongo-protocol' => (isset($_ENV['POKEMONGO_PROTOCOL']) ? $_ENV['POKEMONGO_PROTOCOL'] : 'http'),

    /*
    |--------------------------------------------------------------------------
    | PokemonGo-Map Server IP
    |--------------------------------------------------------------------------
    |
    | The IP address of the PokemonGo-Map server.
    |
    */
    'pokemongo-ip' => (isset($_ENV['POKEMONGO_IP']) ? $_ENV['POKEMONGO_IP'] : '127.0.0.1'),

    /*
    |--------------------------------------------------------------------------
    | PokemonGo-Map Server Port
    |--------------------------------------------------------------------------
    |
    | The port of the PokemonGo-Map server.
    |
    */
    'pokemongo-port' => (isset($_ENV['POKEMONGO_PORT']) ? $_ENV['POKEMONGO_PORT'] : '5000'),

    /*
    |--------------------------------------------------------------------------
    | Pokemon Rarity Level
    |--------------------------------------------------------------------------
    |
    | The Pokemon level of rarity to notify, etc (1-5).
    |
    */
    'pokemon-rarity' => (isset($_ENV['POKEMON_RARITY']) ? $_ENV['POKEMON_RARITY'] : 2),

    /*
    |--------------------------------------------------------------------------
    | Slack server webhook URL
    |--------------------------------------------------------------------------
    |
    | The URL of the Slack server webhook.
    |
    */
    'slack-webhook' => (isset($_ENV['SLACK_WEBHOOK']) ? $_ENV['SLACK_WEBHOOK'] : 'https://hooks.slack.com/services/A/B/C'),

    /*
    |--------------------------------------------------------------------------
    | Slack server channel
    |--------------------------------------------------------------------------
    |
    | The channel of the Slack server.
    |
    */
    'slack-channel' => (isset($_ENV['SLACK_CHANNEL']) ? $_ENV['SLACK_CHANNEL'] : '#general'),

);
