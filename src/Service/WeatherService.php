<?php

namespace Service;

class WeatherService
{
    /**
     * @var array
     */
    var $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getForecast()
    {
        $key = $this->config['key'];
        $location = $this->config['location'];
        $units = $this->config['units'];

        $weather = json_decode(
            file_get_contents(
                'http://api.openweathermap.org/data/2.5/weather?q=' . $location . '&APPID=' . $key . '&units=' . $units
            ),
            true
        );

        return [
            'today' => [
                'type' => $weather['weather'][0]['main'],
                'description' => $weather['weather'][0]['description']
            ],
            'tomorrow' => [
                'type' => isset($weather['weather'][1]) ? $weather['weather'][1]['main'] : 0,
                'description' => isset($weather['weather'][1]) ? $weather['weather'][1]['description'] : 0,
            ],
            'temp' => $weather['main']['temp'],
            'temp_max' => $weather['main']['temp_max'],
            'temp_min' => $weather['main']['temp_min'],
        ];
    }
}
