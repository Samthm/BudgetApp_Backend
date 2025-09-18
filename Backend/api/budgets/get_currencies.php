<?php
// get_currencies.php
header('Content-Type: application/json');

// Beispiel: Währungen aus Datenbank oder Array
$currencies = [
    ['code' => 'EUR', 'name' => 'Euro'],
    ['code' => 'USD', 'name' => 'US-Dollar'],
    ['code' => 'CHF', 'name' => 'Schweizer Franken'],
    // Weitere Währungen...
];

echo json_encode($currencies);
?>