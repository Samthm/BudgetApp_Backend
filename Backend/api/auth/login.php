<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/jwt.php';

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['email']) || empty($data['password'])) {
    http_response_code(400);
    die(json_encode(["message" => "E-Mail und Passwort erforderlich"]));
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$data['email']]);
$user = $stmt->fetch();

if ($user && password_verify($data['password'], $user['password'])) {
    echo json_encode([
        "message" => "Login erfolgreich",
        "token" => generateJWT($user['id'])
    ]);
} else {
    http_response_code(401);
    echo json_encode(["message" => "Falsche Anmeldedaten"]);
}
?>