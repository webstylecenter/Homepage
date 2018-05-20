<?php

namespace App\Service;

use App\Entity\WeatherForecastList;
use App\Service\Adapter\Weather\WeatherAdapterInterface;

class WeatherService
{
    const WEATHER_CACHE_DURATION = 0;

    /**
     * @var WeatherAdapterInterface
     */
    protected $weatherAdapter;

    /**
     * @var \Memcached|null
     */
    protected $cache = null;

    /**
     * @param WeatherAdapterInterface $weatherAdapter
     * @param \Memcached|null $cache
     */
    public function __construct(WeatherAdapterInterface $weatherAdapter, \Memcached $cache = null)
    {
        $this->weatherAdapter = $weatherAdapter;
        $this->cache = $cache;
    }

    /**
     * @return WeatherForecastList|null
     */
    public function getForecastList()
    {
        return $this->cache
            ? $this->cache->get('weather_forecast')
            : $this->fetchForecast();
    }

    /**
     * @return WeatherForecastList|null
     */
    public function fetchForecast()
    {
        $weatherForecast = $this->weatherAdapter->fetchForecast();
        if ($this->cache) {
            $this->cache->set('weather_forecast', $weatherForecast, self::WEATHER_CACHE_DURATION);
        }

        return $weatherForecast;
    }
}
