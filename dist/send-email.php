<?php
// Headers para permitir solicitudes
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Si es una solicitud OPTIONS (preflight), terminamos aquí
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Leer datos enviados (soporta FormData y JSON)
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';

// Intentar leer JSON si $_POST está vacío
if (empty($name) && empty($email) && empty($message)) {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data) {
        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $phone = isset($data['phone']) ? $data['phone'] : '';
        $message = isset($data['message']) ? $data['message'] : '';
    }
}

// Configuración del correo
$to = "ventas@aceaire.com";
$subject = "Nueva Solicitud de Información - Sitio Web ACE AIRE";

// Construir el cuerpo del mensaje
$body = "Ha recibido una nueva solicitud de información:\n\n";
$body .= "Nombre: " . strip_tags($name) . "\n";
$body .= "Correo: " . strip_tags($email) . "\n";
$body .= "Teléfono: " . strip_tags($phone) . "\n";
$body .= "Mensaje:\n" . strip_tags($message) . "\n";

// Configurar los headers del email
// Aseguramos un fallback para HTTP_HOST por si no está definido en CLI/etc
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'aceaire.com';
$headers = "From: noreply@" . $host . "\r\n";
if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $headers .= "Reply-To: " . $email . "\r\n";
}
$headers .= "X-Mailer: PHP/" . phpversion();

// Intentar enviar el correo
if (mail($to, $subject, $body, $headers)) {
    http_response_code(200);
    echo json_encode(["status" => "success", "message" => "Mensaje enviado exitosamente."]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error al enviar el mensaje. Intente nuevamente."]);
}
?>