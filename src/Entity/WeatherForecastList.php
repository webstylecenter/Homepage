<?php

namespace Entity;

/**
 * Class WeatherForecastList
 * @package Entity
 */
class WeatherForecastList
{
    /**
     * @var WeatherForecast
     */
    protected $current;

    /**
     * @var WeatherForecast[]
     */
    protected $upcoming = [];

    /**
     * @return WeatherForecast
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @param WeatherForecast $current
     */
    public function setCurrent($current)
    {
        $this->current = $current;
    }

    /**
     * @return WeatherForecast[]
     */
    public function getUpcoming()
    {
        return $this->upcoming;
    }

    /**
     * @param WeatherForecast $forecast
     */
    public function addUpcoming($forecast)
    {
        $this->upcoming[] = $forecast;
    }
}
