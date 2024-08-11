<?php

namespace App\Services;

use App\Models\MetricHistoryRun;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;

class MetricsService
{
    private Client $client;
    private mixed $apiKey;

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

    public function saveMetrics(string $url, array $metrics, int $strategyId): bool{
        // Extraer las métricas necesarias
        $accessibility = $metrics['accessibility']['score'] ?? 0;
        $pwa = $metrics['pwa']['score'] ?? 0;
        $performance = $metrics['performance']['score'] ?? 0;
        $seo = $metrics['seo']['score'] ?? 0;
        $bestPractices = $metrics['best-practices']['score'] ?? 0;

        // Guardar las métricas en la base de datos
        $model = new MetricHistoryRun();

        $model->fill([
            'url' => $url,
            'accessibility_metric' => $accessibility,
            'pwa_metric' => $pwa,
            'performance_metric' => $performance,
            'seo_metric' => $seo,
            'best_practices_metric' => $bestPractices,
            'strategy_id' => $strategyId
        ]);
        if ($model->save()){
            return true;
        }
        return false;
    }

    private function validateUrl(string $url): bool
    {
        $parsedUrl = parse_url($url);

        if ($parsedUrl === false || !isset($parsedUrl['scheme']) || !in_array($parsedUrl['scheme'], ['http', 'https'])) {
            return false;
        }
        return true;
    }
}
