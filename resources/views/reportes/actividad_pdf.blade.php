<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Actividad</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; font-size: 14px; }
        h2 { text-align: center; color: #155f82; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #155f82; color: white; }
        .print-btn {
            background-color: #155f82;
            color: white;
            padding: 10px 15px;
            margin-bottom: 15px;
            border: none;
            cursor: pointer;
        }
        .no-print {
            text-align: right;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="print-btn" onclick="window.print()">🖨️ Imprimir / Guardar como PDF</button>
    </div>

    <h2>👥 Reporte de Actividad de Documentos</h2>
    <table>
        <thead>
            <tr>
                <th>Documento</th>
                <th>Fecha de Subida</th>
                <th>Última Modificación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($actividad as $a)
                <td>{{ $a->documento }}</td>
                <td>{{ $a->usuario }}</td>
                <td>{{ \Carbon\Carbon::parse($a->fecha_subida)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($a->ultima_modificacion)->format('d/m/Y H:i') }}</td>
            @endforeach
        </tbody>
    </table>
</body>
</html>
