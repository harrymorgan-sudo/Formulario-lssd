<?php
// ⚠️ URL del Webhook de Discord proporcionada por el usuario
$webhookUrl = 'https://discord.com/api/webhooks/1432786419440554058/wVtsVd8RB5jRQbKi-xamfgn1wpi6Y3U32kuefkKdrltIpd-6-58DI8L1tdT9G_ZhGD0k'; 
$status_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Recoger y sanitizar los datos del formulario
    $data = [
        'nombre'            => htmlspecialchars($_POST['nombre'] ?? 'N/A'),
        'telefono'          => htmlspecialchars($_POST['telefono'] ?? 'N/A'),
        'edad'              => htmlspecialchars($_POST['edad'] ?? 'N/A'),
        'licencia_armas'    => htmlspecialchars($_POST['licencia_armas'] ?? 'N/A'),
        'licencia_conducir' => htmlspecialchars($_POST['licencia_conducir'] ?? 'N/A'),
        'antecedentes'      => htmlspecialchars($_POST['antecedentes'] ?? 'N/A'),
        'razones'           => htmlspecialchars($_POST['razones'] ?? 'N/A')
    ];

    // 2. Construir el payload (el mensaje embed de Discord)
    $payload = [
        'username' => 'Oficina del Sheriff - Solicitudes de Empleo',
        'avatar_url' => 'https://i.imgur.com/83p5H3O.png', 
        'embeds' => [
            [
                'title' => '💼 ¡NUEVO CANDIDATO PARA SHERIFF! 📝',
                'description' => 'Una persona ha enviado una solicitud de ingreso.',
                'color' => 3447003, // Color azul
                'fields' => [
                    ['name' => '👤 Nombre Completo', 'value' => $data['nombre'], 'inline' => true],
                    ['name' => '📞 Teléfono de Contacto', 'value' => $data['telefono'], 'inline' => true],
                    ['name' => '🎂 Edad', 'value' => $data['edad'], 'inline' => true],
                    ['name' => '🔫 Licencia Armas', 'value' => $data['licencia_armas'], 'inline' => true],
                    ['name' => '🚗 Licencia Conducir', 'value' => $data['licencia_conducir'], 'inline' => true],
                    ['name' => '⚖️ Antecedentes', 'value' => $data['antecedentes'], 'inline' => false],
                    ['name' => '✍️ Motivación', 'value' => $data['razones'], 'inline' => false]
                ],
                'timestamp' => date('c'),
                'footer' => ['text' => 'Proceso de Reclutamiento']
            ]
        ]
    ];

    $json_payload = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    // 3. Enviar la petición usando cURL
    $ch = curl_init($webhookUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // 4. Manejo del estado del envío
    if ($http_code >= 200 && $http_code < 300) {
        $status_message = 'success';
    } else {
        $status_message = 'error';
    }
}
// Fin del bloque PHP de procesamiento
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oficina del Sheriff - Solicitud de Empleo</title>
    <style>
        /* --- ESTILOS CSS: ESTILO SHERIFF --- */
        body {
            font-family: 'Times New Roman', serif;
            background-color: #f7e4c7; 
            color: #3e2723;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><text x="50%" y="50%" font-size="200" fill="#dac4a4" text-anchor="middle" dominant-baseline="middle" transform="rotate(-10 500 500)">WANTED</text></svg>');
            background-repeat: no-repeat;
            background-position: center 20%;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px 40px;
            border: 5px solid #8d6e63; 
            background-color: #fff8e1; 
            box-shadow: 10px 10px 0px 0px #5d4037; 
        }

        h1 {
            text-align: center;
            color: #1b5e20;
            border-bottom: 3px solid #1b5e20;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-style: italic;
        }

        input[type="text"],
        input[type="tel"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 2px dashed #8d6e63; 
            background-color: #fff;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #1b5e20;
            color: white;
            border: 3px solid #003300;
            font-size: 18px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2e7d32;
        }

        .status-message {
            margin-top: 20px;
            padding: 10px;
            text-align: center;
            display: block;
            border: 2px solid;
            font-weight: bold;
        }

        .status-success {
            border-color: #388e3c;
            background-color: #e8f5e9;
            color: #388e3c;
        }

        .status-error {
            border-color: #d32f2f;
            background-color: #ffebee;
            color: #d32f2f;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>⭐ Solicitud para la Placa de Sheriff ⭐</h1>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

            <?php 
                // Muestra el mensaje de estado si hubo un envío POST previo
                if ($status_message == 'success') {
                    echo '<div class="status-message status-success">¡Solicitud enviada! Nos pondremos en contacto con usted, forastero.</div>';
                } else if ($status_message == 'error') {
                    echo '<div class="status-message status-error">ERROR: No se pudo enviar la solicitud. Revise la URL del Webhook o la configuración del servidor.</div>';
                }
            ?>

            <div class="form-group">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="telefono">Número de Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" pattern="[0-9]{9,}" title="Introduce un número de teléfono válido." required>
            </div>
            
            <div class="form-group">
                <label for="edad">Edad (Mínimo 18 años):</label>
                <input type="number" id="edad" name="edad" min="18" required>
            </div>
            
            <div class="form-group">
                <label for="licencia_armas">¿Tienes licencias de armas?</label>
                <select id="licencia_armas" name="licencia_armas" required>
                    <option value="">-- Seleccione --</option>
                    <option value="Sí">Sí</option>
                    <option value="No">No</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="licencia_conducir">¿Tienes licencias de conducir?</label>
                <select id="licencia_conducir" name="licencia_conducir" required>
                    <option value="">-- Seleccione --</option>
                    <option value="Sí">Sí</option>
                    <option value="No">No</option>
                </select>
            </div>

            <div class="form-group">
                <label for="antecedentes">¿Ha tenido antecedentes penales?</label>
                <select id="antecedentes" name="antecedentes" required>
                    <option value="">-- Seleccione --</option>
                    <option value="Sí (Explicar abajo)">Si</option>
                    <option value="No">No</option>
                </select>
            </div>

            <div class="form-group">
                <label for="razones">Razones por las que quiere unirse a la Oficina del Sheriff:</label>
                <textarea id="razones" name="razones" rows="4" required></textarea>
            </div>

            <button type="submit">Enviar Solicitud de Empleo</button>
        </form>
    </div>
</body>
</html>