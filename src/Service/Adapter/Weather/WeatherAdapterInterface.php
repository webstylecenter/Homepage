<?php

namespace Service\Adapter\Weather;

use Entity\WeatherForecastList;

interface WeatherAdapterInterface
{
    /**
     * @param array $config
     */
    public function __construct(array $config);

    /**
     * @return WeatherForecastList|null
     */
    public function fetchForecast();
}
