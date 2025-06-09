@extends('layouts.pos')

@section('title', 'Reportes')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Dashboard de reportes</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Productos vendidos en periodo -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8v13a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8M16 3.5a4.5 4.5 0 1 1-9 0M3 11h18" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Filtrar por rango de fechas</h3>
            </div>
            <form id="form-fechas" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label for="fecha-inicio" class="block text-sm font-medium text-gray-700 mb-2">Fecha inicio</label>
                    <input type="date" id="fecha-inicio" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" required>
                </div>
                <div>
                    <label for="fecha-fin" class="block text-sm font-medium text-gray-700 mb-2">Fecha fin</label>
                    <input type="date" id="fecha-fin" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" required>
                </div>
                <div class="flex items-end gap-2 md:col-span-2">
                    <button type="submit" form="form-fechas" id="btn-filtrar" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 0 1 1-1h3m10 0h3a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1h-3m-10 0H4a1 1 0 0 1-1-1V4z" />
                        </svg>
                        Consultar
                    </button>
                    <button type="button" id="btn-limpiar" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        Limpiar
                    </button>
                </div>
            </form>
            <div id="tabla-productos-vendidos" class="overflow-x-auto disabled:none"></div>
        </div>
        <!-- Top 3 productos más vendidos -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy-icon lucide-trophy text-yellow-500"><path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"/><path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"/><path d="M18 9h1.5a1 1 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"/><path d="M6 9H4.5a1 1 0 0 1 0-5H6"/></svg>
                    <h3 class="text-lg font-semibold text-gray-900">Top 3 productos más vendidos</h3>
                </div>
            </div>
            <div id="top3-lista" class="p-6 space-y-4"></div>
            <div id="top3-empty" class="text-center py-8 hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 21h6m2 0H7a2 2 0 01-2-2V7h14v12a2 2 0 01-2 2zM7 7V5a5 5 0 0110 0v2" />
                </svg>
                <p class="text-gray-500">No hay datos de ventas disponibles</p>
            </div>
        </div>
    </div>
    <!-- Gráfica de productos más vendidos -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mt-8">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-column-icon lucide-chart-column text-blue-600"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                <h3 class="text-lg font-semibold text-gray-900">Ingresos por ventas de productos</h3>
            </div>
            <p class="text-sm text-gray-600 mt-1">Ingresos de los productos más vendidos</p>
        </div>
        <div class="p-6">
            <div class="h-80">
                <canvas id="grafica-top3" height="320"></canvas>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Productos vendidos en periodo
        document.getElementById('form-fechas').addEventListener('submit', function(e) {
            e.preventDefault();
            const inicio = document.getElementById('fecha-inicio').value;
            const fin = document.getElementById('fecha-fin').value;
            axios.get('http://localhost:8000/api/reporte/productos-vendidos', {
                params: { inicio, fin },
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
            .then(res => {
                const data = res.data;
                let html = '<table class="min-w-full text-left"><thead><tr><th class="px-4 py-2">Producto</th><th class="px-4 py-2">Total vendido</th></tr></thead><tbody>';
                data.forEach(item => {
                    html += `<tr><td class="border px-4 py-2">${item.producto}</td><td class="border px-4 py-2">${item.total_vendido}</td></tr>`;
                });
                html += '</tbody></table>';
                document.getElementById('tabla-productos-vendidos').innerHTML = html;
                // actualizar top3 y gráfica con el rango filtrado
                cargarTop3(inicio, fin);
            })
            .catch(() => {
                document.getElementById('tabla-productos-vendidos').innerHTML = '<p class="text-red-500">Error al consultar datos.</p>';
            });
        });
        // Top 3 productos más vendidos
        // Cambiar cargarTop3 para aceptar parámetros de fecha
        function cargarTop3(inicio, fin) {
            const config = {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            };
            if (inicio && fin) {
                config.params = { inicio, fin };
            }
            axios.get('http://localhost:8000/api/reporte/top3-productos', config)
            .then(res => {
                const data = res.data;
                const container = document.getElementById('top3-lista');
                const empty = document.getElementById('top3-empty');
                container.innerHTML = '';
                if (!data.length) {
                    empty.classList.remove('hidden');
                    container.classList.add('hidden');
                } else {
                    empty.classList.add('hidden');
                    container.classList.remove('hidden');
                    data.forEach((item, index) => {
                        const colors = ['text-yellow-500','text-gray-400','text-amber-600'];
                        const bgs = ['bg-gradient-to-r from-yellow-100 to-yellow-50','bg-gradient-to-r from-gray-100 to-gray-50','bg-gradient-to-r from-amber-100 to-amber-50'];
                        const borders = ['border-yellow-200','border-gray-200','border-amber-200'];
                        const card = document.createElement('div');
                        card.className = `p-4 rounded-lg border transition-all hover:shadow-md ${bgs[index]} ${borders[index]}`;
                        card.innerHTML = `
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy-icon lucide-trophy ${colors[index]}"><path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"/><path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"/><path d="M18 9h1.5a1 1 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"/><path d="M6 9H4.5a1 1 0 0 1 0-5H6"/></svg>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-semibold text-gray-900">${item.producto}</h4>
                                        <span class="text-sm text-gray-500">#${index + 1}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Unidades Vendidas</p>
                                    <p class="text-lg font-bold text-gray-900">${item.total_vendida}</p>
                                </div>
                            </div>`;
                        container.appendChild(card);
                    });
                }
                renderGraficaTop3(data);
            })
            .catch(() => {
                document.getElementById('top3-lista').innerHTML = '<p class="text-red-500">Error al consultar datos.</p>';
            });
        }
        // Gráfica con Chart.js
        let chartTop3;
        function renderGraficaTop3(data) {
            const ctx = document.getElementById('grafica-top3').getContext('2d');
            if (chartTop3) chartTop3.destroy();
            chartTop3 = new window.Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(i => i.producto),
                    datasets: [{
                        label: 'Total vendido',
                        data: data.map(i => i.total_vendida),
                        backgroundColor: ['#60a5fa', '#818cf8', '#fbbf24'],
                        borderColor: ['#3b82f6', '#4f46e5', '#f59e0b'],
                        borderWidth: 1,
                        hoverBackgroundColor: ['#3b82f6', '#4f46e5', '#f59e0b'],
                        hoverBorderColor: ['#2563eb', '#4338ca', '#d97706'],
                        borderRadius: 5,
                        barPercentage: 0.8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 12,
                                    family: 'Inter, system-ui, sans-serif'
                                }
                            }
                        }
                    },
                }
            });
        }
        // Cargar datos al inicio
        document.addEventListener('DOMContentLoaded', function() {
            cargarTop3();
        });
        // Clear filter
        document.getElementById('btn-limpiar').addEventListener('click', function() {
            document.getElementById('fecha-inicio').value = '';
            document.getElementById('fecha-fin').value = '';
            document.getElementById('tabla-productos-vendidos').innerHTML = '';
        });
    </script>
@endsection