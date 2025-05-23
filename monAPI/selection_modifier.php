<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "insertionTeste";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id <= 0) {
        echo json_encode(null);
        exit();
    }

    $stmt = $conn->prepare("SELECT id,numero,designation,loyer FROM appartement WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur de base de données: ' . $e->getMessage()]);
}

$conn = null;
