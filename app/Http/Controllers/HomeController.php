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
     * @param Request $request
     * Metodo que recibe la URL y los queryParams para luego realizar la consulta a la API de Google
     * y devolver el resultado
     * @throws GuzzleException
     * @return JsonResponse
     */
    public function fetchMetrics(Request $request): JsonResponse{
        $url = $request->input('url');
        $categoriesString = $request->input('categories', '');
        $strategy = $request->input('strategy');

        // Convertir la cadena de categorías en un array
        $categories = array_filter(explode(',', $categoriesString));

        // Construir los parámetros de consulta
        $queryParams = [
            'url' => $url,
            'key' => 'AIzaSyDCrPAzhzWxZbJxPYIEURODTvBFVVRNHbY',
            'strategy' => $strategy
        ];

        // Añadir categorías primero
        foreach ($categories as $category) {
            $queryParams['category'][] = $category;
        }

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
