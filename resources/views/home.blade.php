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
<body class="flex flex-col justify-center items-center w-screen gap-2">
    <nav class="w-full h-24 bg-[#503FE0] flex">
        <img class="ml-10" src="https://www.broobe.com/wp-content/uploads/2022/12/logo-broobe.svg" alt="broobe-logo">
        <div class="w-full h-24 bg-[#503FE0] flex justify-center items-center gap-4">
            <button class="text-xl font-medium text-white uppercase border-b-2 border-white">Correr Metricas</button>
            <span class="text-3xl text-white">/</span>
            <button class="text-xl font-medium uppercase text-white">Historial</button>
        </div>
    </nav>
    <div class="mx-auto bg-[#503FE0] p-4 rounded-lg shadow-lg text-white w-full">
        <form id="metricsForm" class="flex flex-col w-full gap-4">

            <!-- Contenedor para mostrar errores -->
            <div id="errorContainer" class="hidden p-4 mb-4 text-white bg-red-500 rounded-md"></div>

            <div class="flex w-full justify-between">
                <div class="form-group w-80">
                    <label for="url" class="block text-sm font-medium">URL:</label>
                    <input type="text" id="url" name="url" class="bg-[#887CF7] mt-1 block w-full px-3 py-2 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <div class="form-group flex mx-10 gap-2 flex-col w-full">
                    <label class="block text-sm font-medium">Categorías:</label>
                    <div class="pt-1">
                        <div class="flex items-center justify-between">
                            @foreach ($categories as $category)
                                <div class="flex items-center">
                                    <input type="checkbox" id="category{{ $category->id }}" name="categories[]" value="{{ $category->name }}" class="categories h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="category{{ $category->id }}" class="ml-2 block text-sm">{{ $category->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group w-80">
                    <label for="strategy" class="block text-sm font-medium">Estrategia:</label>
                    <select id="strategy" name="strategy" class="bg-[#887CF7] mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                        @foreach ($strategies as $strategy)
                            <option value="{{ $strategy->name }}" data-id="{{ $strategy->id }}">{{ $strategy->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" id="submitButton" class="self-start py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md bg-white focus:outline-none text-[#503FE0]">Obtener Métricas</button>
        </form>
    </div>

    <!-- Contenedor para mostrar las métricas -->
    <div id="metricsResults" class="mt-6 flex flex-col items-center">
        <div id="metricsResultsContent" class="flex flex-wrap gap-5 justify-center items-center"></div>
        <button id="saveMetricsButton" class="hidden mt-4 py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Guardar Métricas</button>
    </div>
    <div id="loader" class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-e-transparent align-[-0.125em] text-surface motion-reduce:animate-[spin_1.5s_linear_infinite] dark:text-white" role="status">
        <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
    </div>
    <script>
        const fetchMetricsRoute = "{{ route('home.fetchMetrics') }}";
        const saveMetricsRoute = "{{ route('home.saveMetrics') }}";
    </script>
</body>
</html>
