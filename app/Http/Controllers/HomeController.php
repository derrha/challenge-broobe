<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Strategy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function show():View{
        $categories = Category::all();
        $strategies = Strategy::all();

        return view('home', compact('categories', 'strategies'));
    }

    public function submit(Request $request):RedirectResponse{
        // Aquí manejas la lógica para procesar la URL, categorías y estrategia seleccionadas
        $url = $request->input('url');
        $categories = $request->input('categories', []);
        $strategy_id = $request->input('strategy_id');

        // Puedes realizar las acciones necesarias, como llamar a una API para obtener las métricas

        // Redirigir o mostrar una vista con el resultado
        return redirect()->back()->with('success', 'Métricas obtenidas correctamente.');
    }
}
