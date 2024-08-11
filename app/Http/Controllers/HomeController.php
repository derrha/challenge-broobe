<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Strategy;
use App\Services\PageSpeedService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    private PageSpeedService $pageSpeedService;

    public function __construct(PageSpeedService $pageSpeedService)
    {
        $this->pageSpeedService = $pageSpeedService;
    }

    public function show(): View{
        $categories = Category::all();
        $strategies = Strategy::all();

        return view('home', compact('categories', 'strategies'));
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
            $metrics = $this->pageSpeedService->fetchMetrics($url, $categories, $strategy);

            return response()->json($metrics);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage(), 'details' => $e->getPrevious()->getMessage()], 500);
        }
    }
}
