<?php

namespace App\Service\Adapter\Weather;

use App\Entity\WeatherForecastList;

interface WeatherAdapterInterface
{
    public function __construct();

    /**
     * @return WeatherForecastList|null
     */
    public function fetchForecast();
}
