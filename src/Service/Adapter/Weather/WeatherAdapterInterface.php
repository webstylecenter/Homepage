<?php

namespace Service\Adapter\Weather;

use Doctrine\DBAL\Connection;
use Entity\WeatherForecastList;

/**
 * Interface WeatherAdapterInterface
 * @package Service\Adapter\Weather
 */
interface WeatherAdapterInterface
{
    /**
     * @param array $config
     * @param Connection $database
     */
    public function __construct(array $config, Connection $database);

    /**
     * @return WeatherForecastList
     */
    public function getForecast();

    /**
     * @return string
     */
    public function updateForecast();
}
