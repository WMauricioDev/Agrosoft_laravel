<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Usuarios Activos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }

        .header {
            width: 100%;
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 100px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
        }

        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }

        .header-date {
            text-align: right;
            font-size: 10px;
        }

        h2 { text-align: center; margin-top: 10px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f0f0f0; }

        .section { margin-top: 20px; }
        .justify { text-align: justify; }
    </style>
</head>
<body>

<div class="header">
    <table class="header-table">
        <tr>
            <td style="width: 120px;">
                <img src="{{ public_path('src/def_AGROSIS_LOGOTIC.png') }}" class="logo">
            </td>
            <td class="header-title">
                <div>Centro de gestión y desarrollo sostenible surcolombiano</div>
                <div><strong>SENA - YAMBORÓ</strong></div>
            </td>
            <td class="header-date">
                <div>{{ \Carbon\Carbon::now()->format('Y-m-d') }}</div>
                <div>Página 1 de 1</div>
            </td>
        </tr>
    </table>
</div>

<h2>Informe de Usuarios Activos</h2>

<div class="section">
    <strong>1. Objetivo</strong>
    <p class="justify">
        Este documento presenta un resumen detallado de los Usuarios activos hasta la fecha en el sistema,
        incluyendo información de los mismos. El objetivo es proporcionar una visión general de los usuarios
        presentes en el sistema y control sobre sus roles.
    </p>
</div>

<div class="section">
    <strong>2. Detalle de Usuarios activos</strong>
    <table>
        <thead>
            <tr>
                <th>nombre</th>
                <th>email</th>
                <th>rol</th>
                <th>fecha de registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $u)
                <tr>
                    <td>{{ $u->nombre }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->rol->nombre ?? 'Sin rol' }}</td>
                    <td>{{ \Carbon\Carbon::parse($u->created_at)->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <strong>3. Resumen General</strong>
    <p class="justify">
        Durante el período del {{ $fecha_inicio }} al {{ $fecha_fin }}, se obtuvieron {{ $usuarios->count() }} usuarios activos en el sistema.
    </p>
</div>

</body>
</html>
