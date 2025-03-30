@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Reportes del Sistema</h2>

    <div class="row">
        <div class="col-md-6 mb-4">
            <canvas id="graficoEstado"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="graficoPermisos"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="graficoFechas"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="graficoActividad"></canvas>
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
                    labels: data.permisosPorUsuario.map(e => e.tipo),
                    datasets: [{
                        label: 'Cantidad',
                        data: data.permisosPorUsuario.map(e => e.total),
                        backgroundColor: colores
                    }]
                }
            });

            // 3. Documentos por Fecha (Line)
            new Chart(document.getElementById('graficoFechas'), {
                type: 'line',
                data: {
                    labels: data.documentosPorFecha.map(e => e.mes),
                    datasets: [{
                        label: 'Documentos subidos',
                        data: data.documentosPorFecha.map(e => e.total),
                        borderColor: '#36A2EB',
                        tension: 0.3
                    }]
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
