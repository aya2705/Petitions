<?php
session_start();

define("ROOT", dirname(__DIR__));
require_once(ROOT . "/DB/models/Signature.php");
require_once(ROOT . "/DB/models/Petition.php");

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $action = $_GET['action'] ?? null;
    $petitionId = $_GET['idp'] ?? null;
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? null;
    $petitionId = $_POST['idp'] ?? null;
}

try {
    if ($action === "add" && $_SERVER["REQUEST_METHOD"] === "POST") {
        $data = [
            'idp' => $_POST['idp'],
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'pays' => $_POST['pays'],
            'date' => date('Y-m-d'),
            'heure' => date('H:i:s'),
            'email' => $_POST['email'],
        ];

        if (Signature::addSignature($data)) {
            $_SESSION['message'] = "Signature ajoutée avec succès.";
            
            // Update signature count in session
            $newCount = Signature::countSignaturesByPetition($data['idp']);
            $_SESSION['signatureCount'] = $newCount;
            
            // Get petition again to ensure fresh data
            $petition = Petition::getPetitionById($data['idp']);
            if ($petition) {
                $_SESSION['petition'] = $petition;
            }
            
            // Redirect back to the signature form instead of petition list
            header("Location: ../IHM/Signature/Signature.php");
            exit();
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout de la signature.";
            header("Location: ../IHM/Signature/Signature.php");
            exit();
        }
    } else {
        header("Location: ../Traitement/Utilisateurs.php");
        exit();
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur: " . $e->getMessage();
    header("Location: ../IHM/Signature/Signature.php");
    exit();
}
?>