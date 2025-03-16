<?php
session_start();

define("ROOT", dirname(__DIR__));
require_once(ROOT . "/DB/models/Petition.php");
require_once(ROOT . "/DB/models/Signature.php");

$action = $_GET['action'] ?? '';

header('Content-Type: application/json');

switch ($action) {
    case 'check_new_petition':
        $lastPetition = Petition::getLastPetition();
        
        if ($lastPetition) {
            $signatureCount = Signature::countSignaturesByPetition($lastPetition['IDP']);
            
            echo json_encode([
                "id" => $lastPetition['IDP'],
                "titre" => $lastPetition['Titre'],
                "description" => $lastPetition['Description'],
                "date_public" => $lastPetition['DatePublic'],
                "date_fin" => $lastPetition['DateFinP'],
                "porteur" => $lastPetition['PorteurP'],
                "email" => $lastPetition['Email'],
                "signature_count" => $signatureCount
            ]);
        } else {
            echo json_encode(["id" => null]);
        }
        break;
        
    case 'top_petition':
        $topPetition = Petition::getTopPetition();
        
        if ($topPetition) {
            echo json_encode([
                "Titre" => $topPetition['Titre'], 
                "nombre_signatures" => $topPetition['nombre_signatures']
            ]);
        } else {
            echo json_encode(["Titre" => "Aucune pétition", "nombre_signatures" => 0]);
        }
        break;

        case 'get_signature_counts':
            $petition_ids = isset($_GET['ids']) ? explode(',', $_GET['ids']) : [];
            
            if (empty($petition_ids)) {
                echo json_encode(["error" => "No petition IDs provided"]);
                break;
            }
            
            $counts = [];
            foreach ($petition_ids as $id) {
                $id = (int)$id;
                $counts[$id] = Signature::countSignaturesByPetition($id);
            }
            
            echo json_encode([
                "success" => true,
                "counts" => $counts
            ]);
            break;
        
    default:
        echo json_encode(["error" => "Action non reconnue"]);
}
?>