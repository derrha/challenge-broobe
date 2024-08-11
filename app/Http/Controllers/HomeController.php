<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Strategy;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
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
        try {
            $url = $request->input('url');
            $categoriesString = $request->input('categories', '');
            $strategy = $request->input('strategy');

            // Validar entradas
            if (!$this->validationRules($url)) {
                return response()->json(['error' => 'El formato de la URL es incorrecto.'], 400);
            }

            // Convertir la cadena de categorías en un array
            $categories = array_filter(explode(',', $categoriesString));

            // Construir los parámetros de consulta
            $queryParams = [
                'url' => $url,
                'key' => env('PAGESPEED_API_KEY'),
                'strategy' => $strategy
            ];

            // Añadir categorías
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
        } catch (RequestException $e) {
            // Capturar errores relacionados con la solicitud HTTP
            return response()->json(['error' => 'Error al conectarse con la API de Google PageSpeed Insights.', 'details' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Capturar cualquier otro error
            return response()->json(['error' => 'Ocurrió un error inesperado.', 'details' => $e->getMessage()], 500);
        }
    }

    private function validationRules(string $url): bool
    {
        $parsedUrl = parse_url($url);

        // Verificar si la URL tiene un esquema y si es 'http' o 'https'
        if ($parsedUrl === false || !isset($parsedUrl['scheme']) || !in_array($parsedUrl['scheme'], ['http', 'https'])) {
            return false;
        }

        return true;
    }
}
