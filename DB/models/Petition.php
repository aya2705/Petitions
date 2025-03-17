<?php
require_once(dirname(__DIR__) . "/DB.php");

class Petition {
    public static function getAllPetitions() {
        $conn = DB::getConnection();
        

        $tables_result = $conn->query("SHOW TABLES");
        $tables = [];
        while ($row = $tables_result->fetch_array()) {
            $tables[] = $row[0];
        }
        
  
        $petition_table = in_array('petition', $tables) ? 'petition' : 'Petition';
        $signature_table = in_array('signature', $tables) ? 'signature' : 'Signature';
        
        $sql = "SELECT p.*, COUNT(s.IDS) as signature_count 
            FROM $petition_table p 
            LEFT JOIN $signature_table s ON p.IDP = s.IDP 
            GROUP BY p.IDP 
            ORDER BY p.IDP DESC";
                
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
        $sql = "INSERT INTO Petition (Titre, Description, DatePublic, DateFinP, PorteurP, Email, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $data['titre'], $data['description'], $data['datePublic'], $data['dateFinP'], $data['porteurP'], $data['email'], $data['password']);
        
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
    public static function verifyPassword($id, $password) {
        $petition = self::getPetitionById($id);
        return $petition && password_verify($password, $petition['Password']);
    }
    public static function updatePetition($id, $data) {
        $conn = DB::getConnection();
        $sql = "UPDATE Petition SET Titre = ?, Description = ?, DateFinP = ?, PorteurP = ?, Email = ? WHERE IDP = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $data['titre'], $data['description'], $data['dateFinP'], $data['porteurP'], $data['email'], $id);
        
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Erreur lors de la mise à jour de la pétition : " . $stmt->error);
            return false;
        }
    }
    
    public static function deletePetition($id) {
        $conn = DB::getConnection();
        $sql = "DELETE FROM Petition WHERE IDP = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Erreur lors de la suppression de la pétition : " . $stmt->error);
            return false;
        }
    }
}
?>