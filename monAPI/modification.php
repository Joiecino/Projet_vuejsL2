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

    if (!isset($data['numero']) || !isset($data['designation']) || !isset($data['loyer'])) {
        echo json_encode(['success' => false, 'error' => 'Les champs ID, nom et email sont requis.']);
        exit();
    }

    $id = ($data['id']);
    $numero = ($data['numero']);
    $designation = ($data['designation']);
    $loyer = ($data['loyer']);


    $stmt = $conn->prepare("UPDATE appartement SET numero = :numero, designation = :designation, loyer = :loyer WHERE  id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':numero', $numero);
    $stmt->bindParam(':designation', $designation);
    $stmt->bindParam(':loyer', $loyer);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Informations de l\'utilisateur mises à jour avec succès.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Aucune modification effectuée (ID non trouvé ou données identiques?).']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur de base de données: ' . $e->getMessage()]);
}

$conn = null;
