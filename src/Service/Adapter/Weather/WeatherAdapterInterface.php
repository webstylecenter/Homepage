<?php

namespace Service\Adapter\Weather;

use Entity\WeatherForecastList;

/**
 * Interface WeatherAdapterInterface
 * @package Service\Adapter\Weather
 */
interface WeatherAdapterInterface
{
    /**
     * @param array $config
     */
    public function __construct(array $config);

    /**
     * @return WeatherForecastList
     */
    public function getForecast();
}
