<?php

namespace App\Service\Adapter\Weather;

use App\Entity\WeatherForecast;
use App\Entity\WeatherForecastList;

class Dummy implements WeatherAdapterInterface
{
    /**
     * @var array
     */
    protected $types = [
        WeatherForecast::TYPE_RAIN,
        WeatherForecast::TYPE_CLOUD,
        WeatherForecast::TYPE_CLOUD,
        WeatherForecast::TYPE_CLOUD,
        WeatherForecast::TYPE_PARTLY_CLOUD,
        WeatherForecast::TYPE_SNOW,
        WeatherForecast::TYPE_SUN,
        WeatherForecast::TYPE_THUNDER
    ];

    public function __construct()
    {
        // Intentionally left empty
    }

    /**
     * @return WeatherForecastList
     */
    public function fetchForecast()
    {
        $weatherForecastList = new WeatherForecastList();

        for ($i = 0; $i <= 5; $i++) {
            $forecastItem = new WeatherForecast();
            $forecastItem->setTemperature(mt_rand(5, 420) / 10);
            $forecastItem->setMaxTemperature(mt_rand(5, 420) / 10);
            $forecastItem->setMinTemperature(mt_rand(5, 420) / 10);
            $forecastItem->setType($this->types[array_rand($this->types)]);
            $forecastItem->setDescription('Dummy description');

            if ($i === 0) {
                $weatherForecastList->setCurrent($forecastItem);
            } else {
                $weatherForecastList->addUpcoming($forecastItem);
            }
        }

        return $weatherForecastList;
    }
}
