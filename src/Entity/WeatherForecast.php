<?php

namespace Entity;

/**
 * Class WeatherForecast
 * @package Entity
 */
class WeatherForecast
{
    const TYPE_CLOUD = 'cloud';
    const TYPE_RAIN = 'rain';
    const TYPE_PARTLY_CLOUD = 'partly_cloud';
    const TYPE_SUN = 'sun';
    const TYPE_SNOW = 'snow';
    const TYPE_THUNDER = 'thunderstorm';
    const TYPE_UNKNOWN = 'unknown';

    /**
     * @var double
     */
    protected $temperature;

    /**
     * @var double
     */
    protected $maxTemperature;

    /**
     * @var double
     */
    protected $minTemperature;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $description;

    /**
     * @return float
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * @param float $temperature
     */
    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;
    }

    /**
     * @return float
     */
    public function getMaxTemperature()
    {
        return $this->maxTemperature;
    }

    /**
     * @param float $maxTemperature
     */
    public function setMaxTemperature($maxTemperature)
    {
        $this->maxTemperature = $maxTemperature;
    }

    /**
     * @return float
     */
    public function getMinTemperature()
    {
        return $this->minTemperature;
    }

    /**
     * @param float $minTemperature
     */
    public function setMinTemperature($minTemperature)
    {
        $this->minTemperature = $minTemperature;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
