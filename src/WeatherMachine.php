<?php

namespace BrookeDot\WeatherMachine;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherMachine
{
    protected string $apiKey;

    protected string $endpoint = 'https://weathermachine.io/forecast/';

    protected ?float $lat = null;

    protected ?float $lon = null;

    protected string $source = 'mock';

    protected array $params = [];

    protected HttpClientInterface $client;

    protected array $availableSources = [
        'accuweather',
        'aeris_weather',
        'apple_weather',
        'custom_weather',
        'foreca',
        'mock',
        'open_weather',
        'pirate_weather',
        'the_weather_company',
        'tomorrow_io',
        'visual_crossing',
        'weatherbit',
    ];

    protected array $availableUnits = [
        'us',
        'si',
        'uk',
        'ca',
        'm',
    ];

    protected array $availableOutputs = [
        'base',
        'full',
    ];

    /**
     * Weather Machine constructor.
     */
    public function __construct(string $apiKey, ?HttpClientInterface $client = null)
    {
        $this->apiKey = $apiKey;
        $this->endpoint = $this->endpoint . $apiKey;
        $this->client = $client ?? HttpClient::create();
        $this->params['units'] = 'us';
        $this->params['output'] = 'base';
    }

    /**
     * Sets the latitude and longitude. Must be set
     *
     * @return $this
     */
    public function location(float $lat, float $lon): self
    {
        $this->lat = $lat;
        $this->lon = $lon;

        return $this;
    }

    /**
     * Sets the weather data source
     *
     * @return $this
     */
    public function source(string $source): self
    {
        if (!in_array($source, $this->availableSources)) {
            trigger_error('Invalid source specified, defaulting to mock. Use source() to set it. See: https://weathermachine.io/docs#adding-sources', E_USER_WARNING);
            $this->source = 'mock';

            return $this;
        }
        $this->source = $source;

        return $this;
    }

    /**
     * Builds the endpoint url and sends the get request
     *
     * @return object
     */
    public function get(): object
    {
        if (!$this->lat || !$this->lon) {
            throw new \InvalidArgumentException('Latitude and longitude are required. Use location() to set them.');
        }

        $this->params['source'] = $this->source;

        $url = $this->endpoint . '/' . $this->lat . ',' . $this->lon;

        $response = $this->client->request('GET', $url, [
            'query' => $this->params,
        ]);

        return json_decode($response->getContent());
    }

    /**
     * Sets the return units
     *
     * @return $this
     */
    public function units(string $unit): self
    {
        if (!in_array($unit, $this->availableUnits)) {
            trigger_error('Invalid unit specified, setting to us.', E_USER_WARNING);
            $this->params['units'] = 'us';

            return $this;
        }
        $this->params['units'] = $unit;

        return $this;
    }

    /**
     * Sets the return output format
     *
     * @return $this
     */
    public function output(string $output): self
    {
        if (!in_array($output, $this->availableOutputs)) {
            trigger_error('Invalid output specified, setting to base.', E_USER_WARNING);
            $this->params['output'] = 'base';

            return $this;
        }
        $this->params['output'] = $output;

        return $this;
    }

    // /////////////////////////////////////////////////////////////////
    // ////////////////////////// HELPERS //////////////////////////////
    // /////////////////////////////////////////////////////////////////

    /**
     * Filters out metadata to get only currently
     */
    public function currently(): object
    {
        return $this->get()->currently;
    }

    /**
     * Filters out metadata to get only minutely
     */
    public function minutely(): array
    {
        return $this->get()->minutely->data;
    }

    /**
     * Filters out metadata to get only hourly
     */
    public function hourly(): array
    {
        return $this->get()->hourly->data;
    }

    /**
     * Filters out metadata to get only daily
     */
    public function daily(): array
    {
        return $this->get()->daily->data;
    }

    /**
     * Filters out metadata to get only alerts
     */
    public function alerts(): array
    {
        return $this->get()->alerts->data ?? [];
    }
}