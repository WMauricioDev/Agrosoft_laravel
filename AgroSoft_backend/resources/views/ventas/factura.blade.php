<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: monospace;
            font-size: 9px;
            margin: 0;
            padding: 0;
        }
        .center {
            text-align: center;
        }
        .line {
            border-top: 1px dashed #000;
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 1px 2px;
        }
    </style>
</head>
<body>
    <div class="center">
        <strong>Agrosoft</strong><br>
        NIT: 541235<br>
        Centro de Gestión Surcolombiano<br>
        TELS: 3132132123<br>
        <div class="line"></div>
        <strong>FACTURA DE VENTA</strong><br>
        Factura: {{ $venta->id }}<br>
        Fecha: {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}<br>
        <div class="line"></div>
    </div>

    <table>
        <thead>
            <tr>
                <td>Prod(ID)</td>
                <td>Cant</td>
                <td>V.Unit</td>
                <td>Total</td>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalles as $d)
                <tr>
                    <td>{{ $d->producto }}</td>
                    <td>{{ $d->cantidad }}</td>
                    <td>${{ number_format($d->precio_unitario ?? 0, 2) }}</td>
                    <td>${{ number_format($d->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>
    <div>
        <strong>Total:</strong> ${{ number_format($venta->detalles->sum('total'), 2) }}<br>
        <strong>Entregado:</strong> ${{ number_format($venta->monto_entregado, 2) }}<br>
        <strong>Cambio:</strong> ${{ number_format($venta->cambio, 2) }}<br>
    </div>

    <div class="center line"></div>
    <div class="center">
        ¡Gracias por su compra!<br>
        Software by Agrosoft
    </div>
</body>
</html>
