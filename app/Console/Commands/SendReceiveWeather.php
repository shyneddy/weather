<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\ReceiveWeatherMail;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\WeatherController;
use GuzzleHttp\Client;

class SendReceiveWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    
    protected $signature = 'app:send-receive-weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $subscribers = Subscriber::all();
        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->user->email)->send(new ReceiveWeatherMail($this->getWeather($subscriber->location)));
        }

        $this->info('Daily weather reports sent successfully!');
    }

    public function getWeather($location){
        $client = new Client();
        $currentResponse = $client->get('https://api.weatherapi.com/v1/current.json', [
            'query' => [
                'key' => env('WEATHER_API_KEY'),
                'q' => $location
            ]
        ]);

        $currentData = json_decode($currentResponse->getBody(), true);
        return $currentData;
    }
}
