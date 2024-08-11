<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MetricHistoryRun;
use App\Models\Strategy;
use App\Services\MetricsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    private MetricsService $metricsService;

    public function __construct(MetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    public function show(): View{
        $categories = Category::all();
        $strategies = Strategy::all();

        //Formatear para UX
        $categories = $this->formatCategories($categories);

        return view('metrics', compact('categories', 'strategies'));
    }

    public function showHistory(): View{
        $metricHistory = MetricHistoryRun::with('strategy')->orderBy('created_at', 'desc')->get();
        return view('history', ['metricHistory' => $metricHistory]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchMetrics(Request $request): JsonResponse{
        try {
            $url = $request->input('url');
            $categoriesString = $request->input('categories', '');
            $strategy = $request->input('strategy');

            // Convertir la cadena de categorías en un array
            $categories = array_filter(explode(',', $categoriesString));

            // Obtener métricas desde el servicio
            $metrics = $this->metricsService->fetchMetrics($url, $categories, $strategy);

            return response()->json($metrics);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage(), 'details' => $e->getPrevious()->getMessage()], 500);
        }
    }

    public function saveMetrics(Request $request): JsonResponse
    {
        $url = $request->input('url');
        $metrics = $request->input('metrics');
        $strategyId = $request->input('strategy_id');

        try {
            $this->metricsService->saveMetrics($url, $metrics, $strategyId);
            return response()->json(['success' => 'Métricas guardadas exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar las métricas.', 'details' => $e->getMessage()], 500);
        }
    }

    private function formatCategories($categories): array {
        return $categories->map(function($category) {
            $categoryLabels = [
                'ACCESSIBILITY' => 'Accesibilidad',
                'BEST_PRACTICES' => 'Mejores Practicas',
                'PERFORMANCE' => 'Rendimiento',
                'PWA' => 'PWA',
                'SEO' => 'SEO',
            ];

            // Añadir la propiedad 'label' para la UX
            $category->label = $categoryLabels[$category->name] ?? $category->name;

            // Retornar el objeto de categoría con la nueva propiedad
            return $category;
        })->toArray();
    }
}
