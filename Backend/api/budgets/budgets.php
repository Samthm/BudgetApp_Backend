<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/jwt.php';

header("Content-Type: application/json");

// Hilfsfunktion für Saldo-Berechnung
function berechneSaldo($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT type, amount FROM budgets WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $saldo = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $amount = (float)$row['amount'];
        $saldo += ($row['type'] === 'income') ? $amount : -$amount;
    }
    return number_format($saldo, 2, '.', '');
}

// Authentifizierung
$headers = getallheaders();
$token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');
$user_id = validateJWT($token)->sub ?? null;

if (!$user_id) {
    http_response_code(401);
    die(json_encode(["message" => "Nicht autorisiert"]));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM budgets WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $budgets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => [
            'budgets' => $budgets,
            'saldo' => berechneSaldo($pdo, $user_id)
        ]
    ]);
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (empty($data['category_id']) || empty($data['amount']) || empty($data['type'])) {
        http_response_code(400);
        die(json_encode(["success" => false, "message" => "category_id, amount und type erforderlich"]));
    }

    $stmt = $pdo->prepare("INSERT INTO budgets (user_id, category_id, amount, type) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $data['category_id'], $data['amount'], $data['type']])) {
        echo json_encode([
            "success" => true,
            "message" => "Budget hinzugefügt",
            "id" => $pdo->lastInsertId(),
            "new_saldo" => berechneSaldo($pdo, $user_id)
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Datenbankfehler"]);
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Ihr bestehender DELETE-Code
}
else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Methode nicht erlaubt"]);
}
?>