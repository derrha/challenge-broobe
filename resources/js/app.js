document.addEventListener('DOMContentLoaded', function() {
    let latestMetrics = null;

    const loader = document.getElementById('loader');
    const errorContainer = document.getElementById('errorContainer');
    const submitButton = document.getElementById('submitButton');

    document.getElementById('metricsForm').addEventListener('submit', function(event) {
        event.preventDefault();

        errorContainer.classList.add('hidden');
        errorContainer.innerHTML = '';

        submitButton.textContent = 'Cargando...';
        loader.classList.remove('hidden');
        document.getElementById('noMetricsMessage').innerHTML = '';

        const url = document.querySelector('#url').value.trim();
        const selectedCategories = Array.from(document.querySelectorAll('.categories:checked')).map(cb => cb.value);
        const strategySelect = document.querySelector('#strategy');
        const strategy = strategySelect.value.trim();
        const strategyId = strategySelect.options[strategySelect.selectedIndex].getAttribute('data-id');

        const allCategories = Array.from(document.querySelectorAll('.categories')).map(cb => cb.value);
        const categories = selectedCategories.length > 0 ? selectedCategories : allCategories;

        let queryParams = new URLSearchParams({
            url: url,
            categories: categories.join(','),
            strategy: strategy
        }).toString();

        fetch(`${fetchMetricsRoute}?${queryParams}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => {
                submitButton.textContent = 'Nueva Consulta';
                loader.classList.add('hidden');

                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.error || 'Error al procesar la solicitud.');
                    });
                }
                return response.json();
            })
            .then(data => {
                let resultsDiv = document.getElementById('metricsResultsContent');
                resultsDiv.innerHTML = '';
                latestMetrics = data.lighthouseResult.categories;

                if (!document.getElementById('resultHeader')) {
                    const resultHeader = document.createElement('h2');
                    resultHeader.id = 'resultHeader';
                    resultHeader.className = 'text-xl font-semibold mb-4';
                    resultHeader.textContent = 'Resultado:';
                    document.getElementById('metricsResults').insertBefore(resultHeader, resultsDiv);
                }

                for (let key in latestMetrics) {
                    let category = latestMetrics[key];
                    let metricDiv = document.createElement('div');
                    metricDiv.className = 'p-4 bg-[#e23e80] text-white rounded-md shadow flex justify-center items-center';

                    metricDiv.innerHTML = `<strong>${category.title}</strong>: ${category.score}`;
                    resultsDiv.appendChild(metricDiv);
                }

                const saveButton = document.getElementById('saveMetricsButton');
                saveButton.classList.remove('hidden');
                saveButton.addEventListener('click', function() {
                    fetch(saveMetricsRoute, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            url: url,
                            metrics: data.lighthouseResult.categories,
                            strategy_id: strategyId
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Métricas guardadas exitosamente.');
                            } else {
                                console.error('Error al guardar las métricas:', data.details);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            })
            .catch(error => {
                loader.classList.add('hidden');
                errorContainer.innerHTML = error.message;
                errorContainer.classList.remove('hidden');
                submitButton.textContent = 'Nueva Consulta';
            });
    });
});
