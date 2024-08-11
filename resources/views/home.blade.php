<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Métricas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col h-screen w-screen bg-gray-100">
<nav class="w-full h-24 bg-[#503FE0] flex items-center">
    <img class="ml-4 sm:ml-10 h-8" src="https://www.broobe.com/wp-content/uploads/2022/12/logo-broobe.svg" alt="broobe-logo">
    <div class="w-full h-24 bg-[#503FE0] flex justify-center items-center gap-4">
        <button class="text-lg sm:text-xl font-medium text-white uppercase border-b-2 border-white">Correr Métricas</button>
        <span class="text-2xl sm:text-3xl text-white">/</span>
        <button class="text-lg sm:text-xl font-medium uppercase text-white">Historial</button>
    </div>
</nav>

<div class="flex flex-col sm:flex-row flex-grow">
    <!-- Contenedor para el formulario -->
    <div class="w-full sm:w-72 bg-[#503FE0] p-4 shadow-lg text-white">
        <form id="metricsForm" class="flex flex-col w-full gap-6">
            <!-- Contenedor para mostrar errores -->
            <div id="errorContainer" class="hidden p-4 mb-4 text-white bg-red-500 rounded-md"></div>

            <div class="flex flex-col gap-8">
                <div class="form-group">
                    <label for="url" class="block text-xl font-medium">URL:</label>
                    <input type="text" id="url" name="url" class="bg-[#887CF7] mt-3 block w-full px-3 py-2 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <div class="form-group flex flex-col">
                    <label class="block text-xl font-medium">Categorías:</label>
                    <div class="pt-1 flex flex-wrap gap-2 flex-col mt-2" >
                        @foreach ($categories as $category)
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="category{{ $category['id'] }}" name="categories[]" value="{{ $category['name'] }}" class="categories h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="category{{ $category['id'] }}" class="block text-sm">{{ $category['label'] }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label for="strategy" class="block text-xl font-medium">Estrategia:</label>
                    <select id="strategy" name="strategy" class="bg-[#887CF7] mt-3 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                        @foreach ($strategies as $strategy)
                            <option value="{{ $strategy->name }}" data-id="{{ $strategy->id }}">{{ $strategy->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" id="submitButton" class="mt-2 self-start py-2 px-4 w-full border border-transparent shadow-sm text-sm font-medium rounded-md bg-white focus:outline-none text-[#503FE0] hover:bg-[#1D1D20] hover:text-white ease-in-out">Obtener Métricas</button>
        </form>
    </div>

    <!-- Contenedor para mostrar las métricas -->
    <div id="metricsResults" class="flex-grow p-6 flex items-center flex-col">
        <!-- Loader -->
        <div id="loader" class="hidden self-center h-8 w-8 animate-spin rounded-full border-4 border-solid border-red-500 border-e-transparent align-[-0.125em] text-surface motion-reduce:animate-[spin_1.5s_linear_infinite] dark:text-white" role="status">
            <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
        </div>

        <!-- Mensaje cuando no hay métricas -->
        <div id="noMetricsMessage" class="text-center text-lg sm:text-xl font-semibold text-gray-500 ">
            Aún no se han corrido métricas. <br> Realiza una consulta para obtener resultados!
        </div>

        <!-- Contenido de métricas -->
        <div id="metricsResultsContent" class="flex flex-wrap gap-5 justify-center items-center"></div>

        <!-- Botón para guardar métricas -->
        <button id="saveMetricsButton" class="hidden mt-4 py-2 px-4 max-h-12 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#887CF7]">Guardar Métricas</button>
    </div>
</div>
<script>
    const fetchMetricsRoute = "{{ route('home.fetchMetrics') }}";
    const saveMetricsRoute = "{{ route('home.saveMetrics') }}";
</script>
</body>
</html>
