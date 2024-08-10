<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Strategy;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function show(): View
    {
        $categories = Category::all();
        $strategies = Strategy::all();

        return view('home', compact('categories', 'strategies'));
    }

    /**
     * @throws GuzzleException
     */
    public function fetchMetrics(Request $request){
        $url = $request->input('url');
        $categoriesString = $request->input('categories', ''); // Recibe como cadena
        $strategy = $request->input('strategy');

        // Convertir la cadena de categorías en un array
        $categories = array_filter(explode(',', $categoriesString));

        // Construir los parámetros de consulta
        $queryParams = [
            'url' => $url,
            'key' => 'AIzaSyDCrPAzhzWxZbJxPYIEURODTvBFVVRNHbY',
        ];

        // Añadir categorías primero
        foreach ($categories as $category) {
            $queryParams['category'][] = $category;
        }

        // Añadir la estrategia al final
        $queryParams['strategy'] = $strategy;

        // Convertir los parámetros de consulta a un formato plano
        $flatQueryParams = [];
        foreach ($queryParams as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $flatQueryParams[] = "$key=$item";
                }
            } else {
                $flatQueryParams[] = "$key=$value";
            }
        }

        $queryString = implode('&', $flatQueryParams);

        $client = new Client();
        $response = $client->request('GET', 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?' . $queryString);

        return response()->json(json_decode($response->getBody()->getContents()));
    }
}
