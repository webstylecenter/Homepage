<?php

namespace Service;

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
     * @param WeatherAdapterInterface $weatherAdapter
     */
    public function __construct(WeatherAdapterInterface $weatherAdapter)
    {
        $this->weatherAdapter = $weatherAdapter;
    }

    /**
     * @return \Entity\WeatherForecastList
     */
    public function getForecastList()
    {
        return $this->weatherAdapter->getForecast();
    }
}
