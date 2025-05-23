<?php
// Autoriser les requêtes CORS (pour permettre les requêtes depuis votre domaine local - souvent différent avec Vue CLI)
header("Access-Control-Allow-Origin: http://localhost:8080"); // L'URL par défaut du serveur de développement Vue CLI
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Vérifier la méthode de la requête
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(['error' => 'Méthode non autorisée. Seule la méthode POST est acceptée.']);
    exit();
}

// Informations de connexion à la base de données
$servername = "localhost";
$username = "root"; // Nom d'utilisateur par défaut de WAMP
$password = "";     // Mot de passe par défaut de WAMP
$dbname = "insertionTeste";

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Définir le mode d'erreur PDO sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les données JSON envoyées par Vue.js
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (!isset($data['numero']) || !isset($data['designation']) || !isset($data['loyer'])) {
        http_response_code(400); // Mauvaise requête
        echo json_encode(['error' => 'Les champs "nom" et "email" sont requis.']);
        exit();
    }

    $numero = ($data['numero']); // Sécurité contre les attaques XSS
    $designation = ($data['designation']);
    $loyer = ($data['loyer']); // Sécurité contre les attaques XSS

    // Vérifier la validité de l'email
    /* if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400); // Mauvaise requête
        echo json_encode(['error' => 'L\'adresse email n\'est pas valide.']);
        exit();
    }*/

    // Préparer la requête SQL
    $stmt = $conn->prepare("INSERT INTO appartement (numero, designation, loyer) VALUES (:numero, :designation, :loyer)");

    // Lier les paramètres
    $stmt->bindParam(':numero', $numero);
    $stmt->bindParam(':designation', $designation);
    $stmt->bindParam(':loyer', $loyer);

    // Exécuter la requête
    $stmt->execute();

    // Répondre avec un succès
    http_response_code(201); // Créé
    echo json_encode(['message' => 'Utilisateur ajouté avec succès.']);
} catch (PDOException $e) {
    // En cas d'erreur de connexion ou d'insertion
    http_response_code(500); // Erreur interne du serveur
    echo json_encode(['error' => 'Erreur lors de l\'insertion dans la base de données : ' . $e->getMessage()]);
}

$conn = null; // Fermer la connexion
