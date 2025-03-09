<?php
require_once(dirname(__DIR__) . "/DB.php");

class Petition {
    public static function getAllPetitions() {
        $conn = DB::getConnection();
        $sql = "SELECT * FROM Petition ORDER BY DatePublic DESC";
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
}
?>