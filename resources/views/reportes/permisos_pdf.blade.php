<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Documentos</title>
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

    <h2>Reporte de Documentos Subidos</h2>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Fecha de Subida</th>
                <th>Versión</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permisos as $p)
                {{ $p->usuario }}
                {{ $p->documento }}
                {{ ucfirst($p->permiso) }}
            @endforeach
        </tbody>
    </table>
</body>
</html>
