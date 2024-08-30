<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\WeatherHistory;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class WeatherController extends Controller
{

    public function getIndex()
    {
        if (Auth::user()->weather_history) {
            $location = Auth::user()->weather_history->location_history;

            $data = [
                'current' => $this->getWeatherCurrentAPI($location),
                'forecast' => $this->getWeatherForecastAPI($location, 1, 5),
            ];
            
            return view('weathershow', compact('data'));
        } else {
            return view('weathershow');
        }
    }

    public function getWeather(Request $request)
    {
        $location = $request->input('location');

        $data = [
            'current' => $this->getWeatherCurrentAPI($location),
            'forecast' => $this->getWeatherForecastAPI($location, 1, 5),
        ];

        return view('weathershow', compact('data'));
    }

    public function viewMore(Request $request)
    {
        $additionalForecast = $this->getWeatherForecastAPI($request->location, 4, 15);
        return response()->json($additionalForecast);
    }

    public function receiveForecastMail(Request $request){
        if(!Auth::user()->subscribe){
            Subscriber::create([
                'user_id' => Auth::user()->id,
                'location' => $request->input('location')
            ]);
        }
        return redirect('/weather');

    }

    public function unReceiveForecastMail(){
        if(Auth::user()->subscribe){
            $subscribe = Auth::user()->subscribe;
            $subscribe->delete();
        }
        return response()->json('success');

    }

    //Hàm kết nối API lấy dữ liệu

    public function getWeatherCurrentAPI($location){
        $client = new Client();
        $currentResponse = $client->get('https://api.weatherapi.com/v1/current.json', [
            'query' => [
                'key' => env('WEATHER_API_KEY'),
                'q' => $location
            ]
        ]);

        $currentData = json_decode($currentResponse->getBody(), true);

        if (Auth::user()->weather_history) {
            $weather_history = Auth::user()->weather_history;
            $weather_history->update([
                'location_history' => $location,
            ]);
        } else {
            WeatherHistory::create([
                'user_id' => Auth::user()->id,
                'location_history' => $location,

            ]);
        }

        return $currentData;
    }

    public function getWeatherForecastAPI($location, $startDay, $numberDay){
        $client = new Client();
        $forecastResponse = $client->get('https://api.weatherapi.com/v1/forecast.json', [
            'query' => [
                'key' => env('WEATHER_API_KEY'),
                'q' => $location,
                'days' => $numberDay // Số ngày dự báo
            ]
        ]);

        $forecastData = json_decode($forecastResponse->getBody(), true);
        $forecastData = array_slice($forecastData['forecast']['forecastday'], $startDay);
        return $forecastData;
    }

}
