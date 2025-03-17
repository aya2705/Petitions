<?php
session_start();

define("ROOT", dirname(__DIR__));
require_once(ROOT . "/DB/models/Petition.php");
require_once(ROOT . "/DB/models/Signature.php");

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $action = $_GET['action'] ?? null;
    $petitionId = $_GET['id'] ?? null;
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? null;
    $petitionId = $_POST['id'] ?? null;
}

try {
    if ($action === "sign" && $petitionId) {
        $petition = Petition::getPetitionById($petitionId);
        if ($petition) {
            $_SESSION['petition'] = $petition;
            
            $signatureCount = Signature::countSignaturesByPetition($petitionId);
            $_SESSION['signatureCount'] = $signatureCount;
            
            header("Location: ../IHM/Signature/Signature.php");
            exit();
        } else {
            $_SESSION['error'] = "Pétition non trouvée.";
            header("Location: ../IHM/Petition/index.php");
            exit();
        }
    } elseif ($action === "add" && $_SERVER["REQUEST_METHOD"] === "POST") {
        $data = [
            'titre' => $_POST['titre'],
            'description' => $_POST['description'],
            'datePublic' => date('Y-m-d'), 
            'dateFinP' => $_POST['dateFinP'],
            'porteurP' => $_POST['porteurP'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT), // Hash du mot de passe
        ];

        if ($result = Petition::addPetition($data)) {
            $_SESSION['message'] = "Pétition ajoutée avec succès.";
            
            $petitions = Petition::getAllPetitions();
            $_SESSION['petitions'] = $petitions;
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout de la pétition.";
        }
        header("Location: ../IHM/Petition/index.php");
        exit();
    } else {
        $petitions = Petition::getAllPetitions();
        $_SESSION['petitions'] = $petitions;
        header("Location: ../IHM/Petition/index.php");
        exit();
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur: " . $e->getMessage();
    header("Location: ../IHM/Petition/index.php");
    exit();
}
?>