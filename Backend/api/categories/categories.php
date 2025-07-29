<?php
require_once __DIR__ . '/../../config/db.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($categories);
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (empty($data['name'])) {
        http_response_code(400);
        die(json_encode(["message" => "Name erforderlich"]));
    }

    $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    $stmt->execute([$data['name'], $data['description'] ?? '']);
    
    $newCategoryId = $pdo->lastInsertId();
    echo json_encode([
        "message" => "Kategorie hinzugefügt",
        "id" => $newCategoryId
    ]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (empty($data['id'])) {
        http_response_code(400);
        die(json_encode(["message" => "Kategorie-ID erforderlich"]));
    }

    // Prüfen ob Kategorie in Verwendung ist
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM budgets WHERE category_id = ?");
    $stmt->execute([$data['id']]);
    if ($stmt->fetchColumn() > 0) {
        http_response_code(400);
        die(json_encode(["message" => "Kategorie wird in Budgets verwendet"]));
    }

    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$data['id']]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(["message" => "Kategorie gelöscht"]);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Kategorie nicht gefunden"]);
    }
}
else {
    http_response_code(405);
    echo json_encode(["message" => "Methode nicht erlaubt"]);
}
?>