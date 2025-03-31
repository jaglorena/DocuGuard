<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Permisos por Usuario</title>
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

    <h2>Reporte de Permisos por Usuario</h2>
    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Documento</th>
                <th>Permiso</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permisos as $p)
                <tr>
                    <td>{{ $p->usuario }}</td>
                    <td>{{ $p->documento }}</td>
                    <td>{{ ucfirst($p->permiso) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
