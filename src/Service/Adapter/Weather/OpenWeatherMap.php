<?php

namespace Service\Adapter\Weather;

use Doctrine\DBAL\Connection;
use Entity\WeatherForecast;
use Entity\WeatherForecastList;
use function GuzzleHttp\Promise\exception_for;

/**
 * Class OpenWeatherMap
 * @package Service\Adapter\Weather
 */
class OpenWeatherMap implements WeatherAdapterInterface
{
    const API_URL_PREFIX = 'http://api.openweathermap.org/data/2.5/';

    /**
     * @var string
     */
    protected $weatherUrl;

    /**
     * @var string
     */
    protected $forecastUrl;

    /**
     * @var Connection
     */
    protected $database;

    /**
     * @param array $config
     * @param Connection $database
     */
    public function __construct(array $config, Connection $database)
    {
        $key = $config['openWeatherMap']['key'];
        $location = $config['openWeatherMap']['location'];
        $this->weatherUrl = self::API_URL_PREFIX . 'weather?q=' . $location . '&APPID=' . $key . '&units=metric';
        $this->forecastUrl = self::API_URL_PREFIX . 'forecast?q=' . $location . '&APPID=' . $key . '&units=metric';
        $this->database = $database;
    }

    /**
     * @return WeatherForecastList
     */
    public function getForecast()
    {
        return unserialize($this->database->fetchAll('SELECT data FROM cache WHERE cache_id = "weather_forecast" LIMIT 1')[0]['data']);
    }

    /**
     * @return string
     */
    public function updateForecast()
    {
        $weatherForecastList = $this->downloadForecast();

        $this->database->update(
            'cache', [
                'data'=> serialize($weatherForecastList),
                'updated_at'=> date('y-m-d H:i:s')
            ], [
                'cache_id' => 'weather_forecast'
            ]
        );
        return 'Done';
    }

    /**
     * @return WeatherForecastList
     * @throws \Exception
     */
    protected function downloadForecast()
    {
        $weather = json_decode(@file_get_contents($this->weatherUrl), true);
        $forecast = json_decode(@file_get_contents($this->forecastUrl), true);

        if (!is_array($weather) || !is_array($forecast)) {
            throw new \Exception('Weatherdata could not be updated. Failed to load API');
        }

        $weatherForecastList = new WeatherForecastList();
        $weatherForecastList->setCurrent($this->mapForecast($weather));

        foreach ($forecast['list'] as $item) {
            $date = new \DateTime($item['dt_txt']);

            if ($date->format('H') !== '15') {
                continue;
            }

            $weatherForecastList->addUpcoming($this->mapForecast($item));
        }

        return $weatherForecastList;
    }

    /**
     * @param array $forecastArray
     *
     * @return WeatherForecast
     */
    protected function mapForecast(array $forecastArray)
    {
        $weatherForecast = new WeatherForecast();
        $weatherForecast->setTemperature($forecastArray['main']['temp']);
        $weatherForecast->setMaxTemperature($forecastArray['main']['temp_max']);
        $weatherForecast->setMinTemperature($forecastArray['main']['temp_min']);
        $weatherForecast->setType($this->convertType($forecastArray['weather'][0]['main']));
        $weatherForecast->setDescription($forecastArray['weather'][0]['description']);

        return $weatherForecast;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function convertType($type)
    {
        $map = [
            'rain' => WeatherForecast::TYPE_RAIN,
            'clouds' => WeatherForecast::TYPE_CLOUD,
            'mist' => WeatherForecast::TYPE_CLOUD,
            'haze' => WeatherForecast::TYPE_CLOUD,
            'drizzle' => WeatherForecast::TYPE_PARTLY_CLOUD,
            'snow' => WeatherForecast::TYPE_SNOW,
            'clear' => WeatherForecast::TYPE_SUN,
            'thunderstorm' => WeatherForecast::TYPE_THUNDER
        ];

        foreach ($map as $key => $value) {
            if (stripos($type, $key) !== false) {
                return $value;
            }
        }

        return WeatherForecast::TYPE_UNKNOWN;
    }
}
