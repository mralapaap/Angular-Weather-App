<?php

require_once 'weather_functions.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header("Access-Control-Allow-Headers: *");

$apiKey = '64f60853740a1ee3ba20d0fb595c97d5';
$weatherAPI = new WeatherAPI($apiKey);

if (isset($_REQUEST['request'])) {
    $request = explode('/', $_REQUEST['request']);
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'failed request'));
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        switch ($request[0]) {
            case 'weather':
                if (isset($request[1]) && $request[1] == 'city') {
                    $city = $request[2];
                    $units = $request[3] ?? 'metric';
                    echo json_encode($weatherAPI->getWeatherByCity($city, $units));
                } elseif (isset($request[1]) && $request[1] == 'coords') {
                    $lat = $request[2];
                    $lon = $request[3];
                    $units = $request[4] ?? 'metric';
                    echo json_encode($weatherAPI->getWeatherByCoords($lat, $lon, $units));
                } else {
                    http_response_code(400);
                    echo json_encode(array('error' => 'Bad Request: Missing city or coordinates.'));
                }
                break;
        }
        break;
}
