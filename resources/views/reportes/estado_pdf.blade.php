<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte por Estado</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; font-size: 14px; }
        h2 { text-align: center; color: #155f82; }
        table { width: 60%; margin: auto; border-collapse: collapse; margin-top: 30px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #155f82; color: white; }
        .print-btn {
            background-color: #155f82;
            color: white;
            padding: 10px 15px;
            margin: 20px;
            border: none;
            cursor: pointer;
        }
        .no-print {
            text-align: center;
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

    <h2>Reporte de Documentos por Estado</h2>
    <table>
        <thead>
            <tr>
                <th>Estado</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estados as $e)
                <td>{{ ucfirst($e->estado) }}</td>
                <td>{{ $e->total }}</td>
            @endforeach
        </tbody>
    </table>
</body>
</html>
