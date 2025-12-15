# Weather Machine PHP Client

A PHP wrapper for the [Weather Machine API](https://weathermachine.io/).

Uses Sympfony HTTP Client.

## Installation
```bash
composer require brookedot/weather-machine
```

## Usage
```php
use BrookeDot\WeatherMachine\WeatherMachine;

$weather = new WeatherMachine('your-api-key');

// Get hourly forecast
$hourly = $weather->location(47.608013, -122.335167)
    ->source('mock')
    ->units('si')
    ->hourly();

// Get current conditions
$current = $weather->location(47.608013, -122.335167)
    ->source('open_weather')
    ->currently();

// Get daily forecast
$daily = $weather->location(47.608013, -122.335167)
    ->source('open_weather')
    ->daily();
```

## Available Methods

### Required

- `location(float $lat, float $lon)` - Set coordinates
- `source(string $source)` - Set weather data source (defaults to 'mock')

### Optional

- `units(string $unit)` - Set units: us, si, ca, uk, m (default: us)
- `output(string $format)` - Set output format: base, full (default: base)

### Data Retrieval

- `get()` - Get full response
- `currently()` - Get current conditions
- `hourly()` - Get hourly forecast
- `daily()` - Get daily forecast
- `minutely()` - Get minutely forecast
- `alerts()` - Get weather alerts

## Available Sources

- accuweather
- aeris_weather
- apple_weather
- custom_weather
- foreca
- mock
- open_weather
- pirate_weather
- the_weather_company
- tomorrow_io
- visual_crossing
- weatherbit

## License

AGPL V3 or later License - see LICENSE file for details.