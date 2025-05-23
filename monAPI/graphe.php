   <?php
    header("Access-Control-Allow-Origin: http://localhost:8080"); // L'URL par défaut du serveur de développement Vue CLI
    header("Access-Control-Allow-Methods: POST,GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Content-Type: application/json");

    // Vérifier la méthode de la requête
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    // Configuration de la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "insertionTeste";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête pour récupérer les données
        $stmp = $conn->prepare("SELECT sum(loyer) as totale, min(loyer) as mine, max(loyer) as maxe FROM appartement");

        $stmp->execute();
        $result = $stmp->fetchAll(PDO::FETCH_ASSOC);
        //Encodage en JSON pour l'envoi à Vue.js
        echo json_encode($result);
    } catch (PDOException $e) {
        echo json_encode(['error' => "Erreur de requête : " . $e->getMessage()]);
    }
    $conn = null;
    ?>