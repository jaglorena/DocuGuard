@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mt-3">
        <div class="col">
            <h1 class="mb-4 text-center">Reportes del Sistema</h1>
        </div>
    </div>

    <div class="row mt-3 content-center">
        <div class="col-md-6 mb-4">
            <div style="height: 250px;">
                <canvas id="graficoEstado" style="max-height: 100%;"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div style="height: 250px;">
                <canvas id="graficoPermisos" style="max-height: 100%;"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <label for="tipoFecha" class="form-label">Mostrar documentos por:</label>
            <select id="tipoFecha" class="form-select mb-2">
                <option value="mensual" selected>Mes</option>
                <option value="trimestral">Trimestre</option>
            </select>

            <div style="height: 250px;">
                <canvas id="graficoFechas" style="max-height: 100%;"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div style="height: 300px;">
                <canvas id="graficoActividad" style="max-height: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    fetch('{{ route("reportes.data") }}')
        .then(response => response.json())
        .then(data => {
            const colores = ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];

            // 1. Documentos por Estado (Pie)
            new Chart(document.getElementById('graficoEstado'), {
                type: 'pie',
                data: {
                    labels: data.documentosPorEstado.map(e => e.estado),
                    datasets: [{
                        data: data.documentosPorEstado.map(e => e.total),
                        backgroundColor: colores
                    }]
                }
            });

            // 2. Permisos por Usuario (Bar)
            new Chart(document.getElementById('graficoPermisos'), {
                type: 'bar',
                data: {
                    labels: data.permisosPorUsuario.map(e => e.nivel_acceso),
                    datasets: [{
                        label: 'Cantidad',
                        data: data.permisosPorUsuario.map(e => e.total),
                        backgroundColor: colores
                    }]
                }
            });

            // 3. Documentos por Fecha (mensual/trimestral)
            let chartFechas = null;
            const ctxFechas = document.getElementById('graficoFechas').getContext('2d');

            function renderGraficoFechas(dataSet, label) {
                if (chartFechas) chartFechas.destroy();
                chartFechas = new Chart(ctxFechas, {
                    type: 'line',
                    data: {
                        labels: dataSet.map(e => e.periodo),
                        datasets: [{
                            label: label,
                            data: dataSet.map(e => e.total),
                            borderColor: '#36A2EB',
                            tension: 0.3
                        }]
                    }
                });
            }

            renderGraficoFechas(data.documentosPorFecha, 'Documentos subidos (Mes)');

            document.getElementById('tipoFecha').addEventListener('change', function () {
                if (this.value === 'mensual') {
                    renderGraficoFechas(data.documentosPorFecha, 'Documentos subidos (Mes)');
                } else {
                    renderGraficoFechas(data.documentosPorTrimestre, 'Documentos subidos (Trimestre)');
                }
            });

            // 4. Actividad de Usuarios (Bar agrupado)
            new Chart(document.getElementById('graficoActividad'), {
                type: 'bar',
                data: {
                    labels: data.actividadUsuarios.map(u => u.nombre),
                    datasets: [
                        {
                            label: 'Visualizaciones',
                            data: data.actividadUsuarios.map(u => u.visualizaciones),
                            backgroundColor: '#4BC0C0'
                        },
                        {
                            label: 'Ediciones',
                            data: data.actividadUsuarios.map(u => u.ediciones),
                            backgroundColor: '#FF6384'
                        }
                    ]
                }
            });
        });
</script>
@endsection
