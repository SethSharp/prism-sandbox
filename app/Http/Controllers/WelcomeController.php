<?php

namespace App\Http\Controllers;

use EchoLabs\Prism\Tool;
use EchoLabs\Prism\Prism;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    public function __invoke(): View
    {
        $call = Prism::text()
            ->using('openai', 'gpt-4')
            ->withMaxTokens(50)
            ->usingTemperature(0.3)
            ->withPrompt('What is the weather like on the Gold Coast?')
            ->withTools([$this->createWeatherTool()])
            ->withMaxSteps(2);

        $response = $call();

        return view('welcome', [
            'message' => $response->text
        ]);
    }

    protected function createWeatherTool(): Tool
    {
        return (new Tool)->as('weather')
            ->for('Get current weather conditions for a city')
            ->withParameter('city', 'The city to get the weather for.')
            ->using(function ($city) {
               return "The weather in {$city}";
            });
    }
}