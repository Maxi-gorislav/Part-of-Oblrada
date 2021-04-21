<?php

namespace App\Http\Controllers\Front;

use GuzzleHttp\Client;

class WeatherController extends BaseController
{
    /**
     * The current weather.
     * @copyright https://www.apixu.com/
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index() {

        try {
            // Todo: move to config file
            $key = "0a2de79e56564d95b8c220801181803";

            $city = 'lviv';

            if (cache()->has('weather')) {
                $weather = cache('weather');
            } else {
                $url = "http://api.apixu.com/v1/current.json?key=$key&q=$city&=";

                $client = new Client([
                    'base_uri' => $url,
                ]);

                $response = $client->request('GET', $url);

                $weather = json_decode($response->getBody()->getContents(), true);

                cache()->put('weather', $weather, 10);
            }

            switch ($weather['current']['condition']['code']) {
                case 1153:
                case 1186:
                case 1189:
                case 1192:
                case 1195:
                case 1198:
                case 1201:
                case 1204:
                case 1207:
                case 1237:
                case 1249:
                case 1252:
                    $icon = 'rainy'; // Дощ
                    break;
                case 1003:
                case 1006:
                case 1009:
                case 1030: // Туман
                case 1135: // Туман
                case 1147: // Туман
                    $icon = 'cloudy'; // Хмарно
                    break;
                case 1066:
                case 1069:
                case 1072:
                case 1114:
                case 1117:
                case 1210:
                case 1213:
                case 1216:
                case 1219:
                case 1222:
                case 1225:
                case 1255:
                case 1258:
                case 1279:
                case 1282:
                    $icon = 'flurries'; // Сніг
                    break;
                case 1063:
                case 1150:
                case 1180: // Мряка
                case 1183: // Мряка
                    $icon = 'sun-shower'; // Сонячно + дощ
                    break;
                case 1087:
                case 1240:
                case 1243:
                case 1246:
                case 1261:
                case 1264:
                case 1273:
                case 1276:
                    $icon = 'thunder-storm'; // Гроза
                    break;
                case 1000:
                case 1168: // Ожеледиця
                case 1171: // Ожеледиця
                default :
                    $icon = 'sunny'; // Сонячно
                    break;

            }

            $weather['icon'] = $icon;

            return view('front.components.weather', compact('weather'));
        }
        catch (\Exception $exception){
            return '';
        }
    }
}
