<?php

namespace Service\Adapter\Weather;

use Entity\WeatherForecast;
use Entity\WeatherForecastList;

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
     * @param array $config
     */
    public function __construct(array $config)
    {
        $key = $config['openWeatherMap']['key'];
        $location = $config['openWeatherMap']['location'];
        $this->weatherUrl = self::API_URL_PREFIX . 'weather?q=' . $location . '&APPID=' . $key . '&units=metric';
        $this->forecastUrl = self::API_URL_PREFIX . 'forecast?q=' . $location . '&APPID=' . $key . '&units=metric';
    }

    /**
     * @return WeatherForecastList
     */
    public function getForecast()
    {
        return $this->cache ? $this->cache->get('weather_forecast') : null;
    }

    /**
     * @return WeatherForecastList
     */
    public function fetchForecast()
    {
        $forecastData = $this->downloadForecast();
        return $this->createForecastList($forecastData['weather'], $forecastData['forecast']);
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function downloadForecast()
    {
        $weather = json_decode(@file_get_contents($this->weatherUrl), true);
        $forecast = json_decode(@file_get_contents($this->forecastUrl), true);

        if (!is_array($weather) || !is_array($forecast)) {
            throw new \Exception('Weatherdata could not be updated. Failed to load API');
        }

        return [
            'weather' => $weather,
            'forecast' => $forecast
        ];
    }

    /**
     * @param $weather
     * @param $forecast
     *
     * @return WeatherForecastList
     */
    protected function createForecastList($weather, $forecast)
    {
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
