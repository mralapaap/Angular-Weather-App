<?php

class WeatherAPI {
    private $apiKey;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function getWeatherByCity($city, $units) {
        $url = "http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid={$this->apiKey}&units=$units";
        return $this->getWeatherData($url, $units);
    }

    public function getWeatherByCoords($lat, $lon, $units) {
        $url = "http://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid={$this->apiKey}&units=$units";
        return $this->getWeatherData($url, $units);
    }

    private function getWeatherData($url, $units) {
        try {
            $response = file_get_contents($url);
            if ($response === FALSE) {
                throw new Exception('Error fetching data from OpenWeatherMap API');
            }
            $data = json_decode($response, true);
            
            $weatherData = array(
                'name' => $data['name'] ?? 'N/A',
                'country' => $data['sys']['country'] ?? 'N/A',
                'datetime' => date('l, d F Y h:i A', $data['dt'] ?? time()),
                'forecast' => $data['weather'][0]['main'] ?? 'N/A',
                'temperature' => $data['main']['temp'] ?? 'N/A',
                'icon' => "http://openweathermap.org/img/wn/" . ($data['weather'][0]['icon'] ?? '') . "@4x.png",
                'minTemp' => $data['main']['temp_min'] ?? 'N/A',
                'maxTemp' => $data['main']['temp_max'] ?? 'N/A',
                'realFeel' => $data['main']['feels_like'] ?? 'N/A',
                'humidity' => $data['main']['humidity'] ?? 'N/A',
                'wind' => $data['wind']['speed'] ?? 'N/A',
                'windUnit' => $units === 'imperial' ? 'mph' : 'm/s',
                'pressure' => $data['main']['pressure'] ?? 'N/A'
            );
            return $weatherData;
        } catch (Exception $e) {
            http_response_code(500);
            return array('error' => 'Internal Server Error');
        }
    }
}
