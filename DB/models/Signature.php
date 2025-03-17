<?php
require_once(dirname(__DIR__) . "/DB.php");

class Signature {
    public static function getSignaturesByPetition($petitionId) {
        $conn = DB::getConnection();
        $sql = "SELECT * FROM Signature WHERE IDP = ? ORDER BY Date DESC, Heure DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $petitionId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $signatures = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $signatures[] = $row;
            }
        }
        return $signatures;
    }

    public static function addSignature($data) {
        $conn = DB::getConnection();
        $sql = "INSERT INTO Signature (IDP, Nom, Prenom, Pays, Date, Heure, Email) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssss", $data['idp'], $data['nom'], $data['prenom'], $data['pays'], $data['date'], $data['heure'], $data['email']);
        
        return $stmt->execute() ? $conn->insert_id : false;
    }

    public static function countSignaturesByPetition($petitionId) {
        $conn = DB::getConnection();
        $sql = "SELECT COUNT(*) as count FROM Signature WHERE IDP = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $petitionId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['count'];
        }
        return 0;
    }

    public static function getLastSignaturesByPetition($petitionId, $limit = 5) {
        $conn = DB::getConnection();
        $sql = "SELECT * FROM Signature WHERE IDP = ? ORDER BY Date DESC, Heure DESC LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $petitionId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $signatures = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $signatures[] = $row;
            }
        }
        return $signatures;
    }
}
?>