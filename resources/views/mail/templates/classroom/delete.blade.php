<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud para Elementos de programación y estructura de datos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 20px;
        }

        .header img {
            width: 150px;
            height: auto;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }

        .content {
            line-height: 1.5;
        }

        .content p {
            margin: 0;
            color: #555;
        }

        .content table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        .content th,
        .content td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .content a {
            color: #007bff;
            text-decoration: none;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .footer p {
            margin: 0;
            color: #999;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>{{ $details['title'] }}</h1>
    </div>
    <div class="content">
        <p>**Fecha de eliminación:** {{ $details['updated_at'] }}</p>
        <p>El ambiente {{ $details['classroom_name'] }} se elimino del sistema el día {{ $details['updated_at'] }}. Las solicitudes/reservas y demás acontecimientos serán rechazados/cancelados por el mencionado motivo, favor de tomar nota.</p>
    </div>
    <div class="footer">
        <p>**Enviado por:** {{ $details['sender'] }}</p>
        <p>&copy; 2024 SURA. Todos los derechos reservados.</p>
        <p>**Redes sociales:**</
    </div>
</div>
</body>
</html>
