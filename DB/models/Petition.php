<?php
require_once(dirname(__DIR__) . "/DB.php");

class Petition {
    public static function getAllPetitions() {
        $conn = DB::getConnection();
        
        // Debug: Let's see what tables exist
        $tables_result = $conn->query("SHOW TABLES");
        $tables = [];
        while ($row = $tables_result->fetch_array()) {
            $tables[] = $row[0];
        }
        
        // Adjust table names based on what exists in database
        $petition_table = in_array('petition', $tables) ? 'petition' : 'Petition';
        $signature_table = in_array('signature', $tables) ? 'signature' : 'Signature';
        
        $sql = "SELECT p.*, COUNT(s.IDS) as signature_count 
                FROM $petition_table p 
                LEFT JOIN $signature_table s ON p.IDP = s.IDP 
                GROUP BY p.IDP 
                ORDER BY p.DatePublic DESC";
                
        $result = $conn->query($sql);
        
        $petitions = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $petitions[] = $row;
            }
        }
        return $petitions;
    }

    public static function getPetitionById($id) {
        $conn = DB::getConnection();
        $sql = "SELECT * FROM Petition WHERE IDP = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public static function addPetition($data) {
        $conn = DB::getConnection();
        $sql = "INSERT INTO Petition (Titre, Description, DatePublic, DateFinP, PorteurP, Email) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $data['titre'], $data['description'], $data['datePublic'], $data['dateFinP'], $data['porteurP'], $data['email']);
        
        return $stmt->execute() ? $conn->insert_id : false;
    }

    public static function getLastPetition() {
        $conn = DB::getConnection();
        $sql = "SELECT * FROM Petition ORDER BY IDP DESC LIMIT 1";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    // Récupérer la pétition la plus signée
    public static function getTopPetition() {
        $conn = DB::getConnection();
        $sql = "SELECT p.*, COUNT(s.IDS) as nombre_signatures 
                FROM Petition p 
                LEFT JOIN Signature s ON p.IDP = s.IDP 
                GROUP BY p.IDP 
                ORDER BY nombre_signatures DESC 
                LIMIT 1";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}
?>