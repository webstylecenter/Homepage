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
        $weather = json_decode(file_get_contents($this->weatherUrl), true);
        $forecast = json_decode(file_get_contents($this->forecastUrl), true);

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
        if (stripos($type, 'rain') !== false) {
            return WeatherForecast::TYPE_RAIN;
        }

        if (stripos($type, 'clouds') !== false || stripos($type, 'mist') !== false ) {
            return WeatherForecast::TYPE_CLOUD;
        }

        if (stripos($type, 'drizzle') !== false) {
            return WeatherForecast::TYPE_PARTLY_CLOUD;
        }

        if (stripos($type, 'snow') !== false) {
            return WeatherForecast::TYPE_SNOW;
        }

        if (stripos($type, 'clear') !== false) {
            return WeatherForecast::TYPE_SUN;
        }

        if (stripos($type, 'thunderstorm') !== false) {
            return WeatherForecast::TYPE_THUNDER;
        }

        return WeatherForecast::TYPE_UNKNOWN;
    }
}
