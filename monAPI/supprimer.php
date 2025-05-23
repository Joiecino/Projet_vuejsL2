<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "insertionTeste";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"), true);
    $id = isset($data['id']) ? intval($data['id']) : 0;

    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'ID d\'utilisateur invalide.']);
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM appartement WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Utilisateur supprimé avec succès.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Impossible de supprimer l\'utilisateur (ID non trouvé?).']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur de base de données: ' . $e->getMessage()]);
}

$conn = null;
