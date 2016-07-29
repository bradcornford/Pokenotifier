# An easy way to send nearby Pokemon notifications to Slack

[![Latest Stable Version](https://poser.pugx.org/cornford/Pokenotifier/version.png)](https://packagist.org/packages/cornford/pokenotifier)
[![Total Downloads](https://poser.pugx.org/cornford/pokenotifier/d/total.png)](https://packagist.org/packages/cornford/Pokenotifier)
[![Build Status](https://travis-ci.org/bradcornford/Pokenotifier.svg?branch=master)](https://travis-ci.org/bradcornford/Pokenotifier)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bradcornford/Pokenotifier/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bradcornford/Pokenotifier/?branch=master)

Think of Pokenotifier as an easy way to generate Slack notifications for nearby Pokemon using PokemonGo-Map. These include:

- `processScanRequest`
- `processWebhookRequest`
- `requestPokemonGoMapScan`
- `requestPokemonGoMapData`
- `expireCachedItems`
- `sendSlackNotification`
- `processPokemon`
- `loadApplicationConfiguration`
- `getApplicationConfiguration`
- `loadPokemonConfiguration`
- `getPokemonConfiguration`
- `createGuzzleClient`
- `getGuzzleClient`
- `setGuzzleClient`
- `createSlackClient`
- `getSlackClient`
- `setSlackClient`
- `createDirectoryIteratorInstance`
- `getDirectoryIteratorInstance`
- `setDirectoryIteratorInstance`

## Installation

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `cornford/pokenotifier`.

	"require": {
		"cornford/pokenotifier": "1.*"
	}

Next, update Composer from the Terminal:

	composer update

Finally we need to introduce the configuration files into your application/

	cp src/config/{example.,}application.php

That's it! You're all set to go.

## Configuration

You can now configure Pokenotifier in a few simple steps. Open `src/config/application.php` and update the options as needed.

- `default-latitude` - Default latitude used for scan.
- `default-longitude` - Default longitude used for scan.
- `cache-directory` - Cache directory location.
- `pokemongo-protocol` - The protocol of the PokemonGo-Map server.
- `pokemongo-ip` - The IP address of the PokemonGo-Map server.
- `pokemongo-port` - The port of the PokemonGo-Map server.
- `pokemon-rarity` - The Pokemon level of rarity to notify, etc (1-5).
- `slack-webhook` - The URL of the Slack server webhook.
- `slack-channel` - The channel of the Slack server.

## Usage

It's really as simple as using the Pokenotifier class in any Controller / Model / File you see fit with:

`$notifier = new Cornford\Pokenotifier\Notifier();`

This will give you access to

- [Process Scan Request](#process-scan-request)
- [Process Webhook Request](#process-webhook-request)
- [Request Pokemon Go Map Scan](#request-pokemon-go-map-scan)
- [Request Pokemon Go Map Data](#request-pokemon-go-map-data)
- [Expire Cached Items](#expire-cached-items)
- [Send Slack Notification](#send-slack-notification)
- [Process Pokemon](#process-pokemon)
- [Load Application Configuration](#load-application-configuration)
- [Get Application Configuration](#get-application-configuration)
- [Load Pokemon Configuration](#load-pokemon-configuration)
- [Get Pokemon Configuration](#get-pokemon-configuration)
- [Create Guzzle Client](#create-guzzle-client)
- [Get Guzzle Client](#get-guzzle-client)
- [Set Guzzle Client](#set-guzzle-client)
- [Create Slack Client](#create-slack-client)
- [Get Slack Client](#get-slack-client)
- [Set Slack Client](#set-slack-client)
- [Create Directory Iterator](#create-directory-iterator)
- [Get Directory Iterator](#get-directory-iterator)
- [Set Directory Iterator](#set-directory-iterator)

### Process Scan Request

The `processScanRequest` method allows you to process a scan request, using a position object as a parameter.

	$notifier->processScanRequest(new Cornford\Pokenotifier\Models\Position(0, 0));

### Process Scan Request

The `processWebhookRequest` method allows you to process a webhook request, using a position object, and request data as parameters.

	$notifier->processWebhookRequest(new Cornford\Pokenotifier\Models\Position(0, 0), ['pokemons'=> []]);

### Request Pokemon Go Map Scan

The `requestPokemonGoMapScan` method allows you to send a scan request to PokemonGo-Map, using a position object as a parameter.

	$notifier->requestPokemonGoMapScan(new Cornford\Pokenotifier\Models\Position(0, 0));

### Request Pokemon Go Map Data

The `requestPokemonGoMapData` method allows you to send a data request to PokemonGo-Map.

	$notifier->requestPokemonGoMapData();

### Expire Cached Items

The `expireCachedItems` method allows you to expire cached items that haven't been modified for a specified timeout, using a string as a parameter.

	$notifier->expireCachedItems('-1 day');

### Send Slack Notification

The `sendSlackNotification` method allows you to send a Slack notification, using an array of Pokemon data, and a position object as parameters.

	$notifier->sendSlackNotification(['pokemon_id' => '', 'pokemon_name' => '', 'latitude' => '', 'longitude' => '', 'disappear_time' => ''], new Cornford\Pokenotifier\Models\Position(0, 0));

### Process Pokemon

The `processPokemon` method allows you to process an array of Pokemon's and optionally send a Slack notificaiton, using an array of Pokemon data, a position object, and a boolean for notification as parameters.

	$notifier->processPokemon(['pokemons'=> []], new Cornford\Pokenotifier\Models\Position(0, 0), true);

### Load Application Configuration

The `loadApplicationConfiguration` method allows you to load the current application configuration file.

	$notifier->loadApplicationConfiguration();

### Get Application Configuration

The `getApplicationConfiguration` method allows you to get the currently stored application configuration.

	$notifier->getApplicationConfiguration();

### Load Pokemon Configuration

The `loadPokemonConfiguration` method allows you to load the current Pokemon configuration file.

	$notifier->loadPokemonConfiguration();

### Get Pokemon Configuration

The `getPokemonConfiguration` method allows you to get the currently stored Pokemon configuration.

	$notifier->getPokemonConfiguration();

### Create Guzzle Client

The `createGuzzleClient` method allows you to instantiate a new Guzzle Client.

	$notifier->createGuzzleClient();

### Get Guzzle Client

The `getGuzzleClient` method allows you to get the currently instantiated Guzzle Client.

	$notifier->getGuzzleClient();

### Set Guzzle Client

The `setGuzzleClient` method allows you to store a new instantiation of Guzzle Client, using a client object as a parameter.

	$notifier->setGuzzleClient(new GuzzleClient(['base_url' => 'http://www.google.com']));

### Create Slack Client

The `createSlackClient` method allows you to instantiate a new Slack Client.

	$notifier->createSlackClient();

### Get Slack Client

The `getSlackClient` method allows you to get the currently instantiated Slack Client.

	$notifier->getSlackClient();

### Set Slack Client

The `setSlackClient` method allows you to store a new instantiation of Slack Client, using a client object as a parameter.

	$notifier->setSlackClient(new SlackClient('https://hooks.slack.com/services/', [], $notifier->getGuzzleClient()));

### Create Directory Iterator

The `createDirectoryIterator` method allows you to instantiate a new Directory Iterator.

	$notifier->createDirectoryIterator();

### Get Directory Iterator

The `getDirectoryIterator` method allows you to get the currently instantiated Directory Iterator.

	$notifier->getDirectoryIterator();

### Set Directory Iterator

The `setDirectoryIterator` method allows you to store a new instantiation of Directory Iterator, using a iterator object as a parameter.

	$notifier->setDirectoryIterator('./cache');

## Example

For an example usage see `index.php`

### Webhook

You can run a webhook with PokemonGo-Map with the following command, where the `-wh` parameter is the location where the script is located. You can run a local webserver, listening locally to respond to webhook request.

    sudo python /var/www/PokemonGo-Map/runserver.py -wh http://localhost?type=webhook

### Scan

You can run a scan with PokemonGo-Map with the following command, where the post data contains both `lat` and `lon` parameters. You can run an application like Traccar Client, to send requests to your webserver for scan.

    curl --data "lat=53.3811&lon=-1.4701" http://localhost?type=scan

### License

Pokenotifier is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)