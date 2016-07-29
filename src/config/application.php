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
    'default-latitude' => (isset($_ENV['DEFAULT_LATITUDE']) ? $_ENV['DEFAULT_LATITUDE'] : 53.3801890),

    /*
    |--------------------------------------------------------------------------
    | Default Longitude
    |--------------------------------------------------------------------------
    |
    | Default longitude used for scan.
    |
    */
    'default-longitude' => (isset($_ENV['DEFAULT_LONGITUDE']) ? $_ENV['DEFAULT_LONGITUDE'] : -1.4631310),

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
    'pokemongo-ip' => (isset($_ENV['POKEMONGO_IP']) ? $_ENV['POKEMONGO_IP'] : '10.241.128.44'),

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
    'slack-webhook' => (isset($_ENV['SLACK_WEBHOOK']) ? $_ENV['SLACK_WEBHOOK'] : 'https://hooks.slack.com/services/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'),

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
