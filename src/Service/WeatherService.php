<?php

namespace Service;

use Doctrine\DBAL\Connection;
use Service\Adapter\Weather\WeatherAdapterInterface;

/**
 * Class WeatherService
 * @package Service
 */
class WeatherService
{
    /**
     * @var WeatherAdapterInterface
     */
    protected $weatherAdapter;

    /**
     * @var Connection
     */
    protected $database;

    /**
     * @param WeatherAdapterInterface $weatherAdapter
     * @param Connection $database
     */
    public function __construct(WeatherAdapterInterface $weatherAdapter, Connection $database)
    {
        $this->weatherAdapter = $weatherAdapter;
        $this->database = $database;
    }

    /**
     * @return \Entity\WeatherForecastList
     */
    public function getForecastList()
    {
        return $this->weatherAdapter->getForecast();
    }

    /**
     * @return string
     */
    public function updateForecast()
    {
        return $this->weatherAdapter->updateForecast();
    }
}
