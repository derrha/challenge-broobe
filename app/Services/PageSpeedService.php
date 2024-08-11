<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class PageSpeedService
{
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('PAGESPEED_API_KEY');
    }

    public function fetchMetrics(string $url, array $categories, string $strategy)
    {
        try {
            if (!$this->validateUrl($url)) {
                throw new \InvalidArgumentException('El formato de la URL es incorrecto.');
            }

            // Construir los parámetros de consulta
            $queryParams = [
                'url' => $url,
                'key' => $this->apiKey,
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

            $response = $this->client->request('GET', 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?' . $queryString);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new \RuntimeException('Error al conectarse con la API de Google PageSpeed Insights.', 0, $e);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Ocurrió un error inesperado.', 0, $e);
        }
    }

    private function validateUrl(string $url): bool
    {
        $parsedUrl = parse_url($url);

        // Verificar si la URL tiene un esquema y si es 'http' o 'https'
        if ($parsedUrl === false || !isset($parsedUrl['scheme']) || !in_array($parsedUrl['scheme'], ['http', 'https'])) {
            return false;
        }
        return true;
    }
}
