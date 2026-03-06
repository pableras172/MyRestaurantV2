<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credenciales de Acceso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .credentials {
            background-color: #f8f9fa;
            padding: 20px;
            border-left: 4px solid #007bff;
            margin: 20px 0;
        }
        .credentials p {
            margin: 10px 0;
        }
        .credentials strong {
            display: inline-block;
            width: 100px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            color: #777;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>¡Bienvenido a MyRestaurant!</h1>
    </div>

    <div class="content">
        <h2>Hola,</h2>
        <p>Tu restaurante <strong>{{ $restaurant->name }}</strong> ha sido creado exitosamente en nuestra plataforma.</p>
        
        <p>Se ha creado una cuenta de administrador para que puedas gestionar tu restaurante. A continuación encontrarás tus credenciales de acceso:</p>

        <div class="credentials">
            <p><strong>Usuario:</strong> {{ $email }}</p>
            <p><strong>Contraseña:</strong> {{ $password }}</p>
            <p><strong>URL de acceso a la vista del menú:</strong> {{ config('app.url') }}/{{ $restaurant->slug }}/menu</p>
            <p><strong>URL de acceso a la administración:</strong> {{ config('app.url') }}/administrador</p>
        </div>

        <div class="warning">
            <strong>⚠️ Importante:</strong> Por seguridad, te recomendamos cambiar tu contraseña después del primer inicio de sesión.
        </div>

        <div style="text-align: center;">
            <a href="{{ config('app.url') }}/administrador" class="btn">Acceder al Panel de Administración</a>
        </div>

        <p>Desde el panel de administración podrás:</p>
        <ul>
            <li>Configurar tu restaurante</li>
            <li>Crear y gestionar menús</li>
            <li>Añadir productos y categorías</li>
            <li>Personalizar el tema y estilo</li>
            <li>Y mucho más...</li>
        </ul>

        <p>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.</p>

        <p>¡Gracias por confiar en MyRestaurant!</p>
    </div>

    <div class="footer">
        <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
        <p>&copy; {{ date('Y') }} MyRestaurant. Todos los derechos reservados.</p>
    </div>
</body>
</html>
