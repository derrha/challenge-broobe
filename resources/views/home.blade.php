<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Métricas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-800 flex flex-col justify-center items-center w-screen gap-2">
<nav class="w-full h-24 bg-gray-900 flex justify-center items-center">
    <h1 class="text-3xl text-white font-bold text-center uppercase">Challenge Broobe</h1>
</nav>
<div class="w-full h-24 bg-gray-900 flex justify-center items-center gap-4">
    <button class="text-xl text-white border-b-4 border-red-500">Run Metric</button>
    <button class="text-xl text-white">Metric History</button>
</div>
<div class="mx-auto bg-gray-900 p-4 rounded-lg shadow-lg text-white w-full">
    <form id="metricsForm" class="flex flex-col w-full gap-4">
        <div class="flex w-full justify-between">
            <div class="form-group w-80">
                <label for="url" class="block text-sm font-medium">URL:</label>
                <input type="text" id="url" name="url" class="bg-gray-600 mt-1 block w-full px-3 py-2 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
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
                <select id="strategy" name="strategy" class="bg-gray-600 mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                    @foreach ($strategies as $strategy)
                        <option value="{{ $strategy->name }}">{{ $strategy->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="self-start py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Obtener Métricas</button>
    </form>

    <!-- Contenedor para mostrar las métricas -->
    <div id="metricsResults" class="mt-6"></div>
</div>

<script>
    document.getElementById('metricsForm').addEventListener('submit', function(event) {
        event.preventDefault();

        // Obtener los valores del formulario
        const url = document.querySelector('#url').value.trim();
        const categories = Array.from(document.querySelectorAll('.categories:checked')).map(cb => cb.value);
        const strategy = document.querySelector('#strategy').value.trim();

        // Construir la URL de la solicitud con parámetros de consulta
        let queryParams = new URLSearchParams({
            url: url,
            categories: categories.join(','),
            strategy: strategy
        }).toString();

        fetch(`{{ route('home.fetchMetrics') }}?${queryParams}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log(data.lighthouseResult);
                let resultsDiv = document.getElementById('metricsResults');
                resultsDiv.innerHTML = '';

                // Mostrar resultados
                for (let key in data.lighthouseResult.categories) {
                    let category = data.lighthouseResult.categories[key];
                    let metricDiv = document.createElement('div');
                    metricDiv.className = 'mt-2 p-4 bg-gray-100 text-gray-900 rounded-md';

                    metricDiv.innerHTML = `<strong>${category.title}:</strong> ${category.score * 100}`;
                    resultsDiv.appendChild(metricDiv);
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>
</body>
</html>
