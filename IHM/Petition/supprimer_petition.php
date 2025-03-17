<?php
session_start();
require_once("../../DB/models/Petition.php");

$id = $_GET['id'] ?? null;
$petition = Petition::getPetitionById($id);

if (!$petition) {
    $_SESSION['error'] = "Pétition non trouvée.";
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST['password'] ?? '';
    
    if (password_verify($password, $petition['password'])) {
       
        if (Petition::deletePetition($id)) {
         
            $petitions = Petition::getAllPetitions();
            $_SESSION['petitions'] = $petitions;

            $_SESSION['message'] = "Pétition supprimée avec succès.";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression de la pétition.";
        }
    } else {
        $_SESSION['error'] = "Mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer la Pétition</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
            background-color: #f4f4f4;
        }
        .container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px #aaa;
            text-align: center;
        }
        h1 {
            color: #d9534f;
        }
        label {
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #d9534f;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background: #c9302c;
        }
        .cancel {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Supprimer la Pétition</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div style="color: red;"><?= $_SESSION['error']; ?></div>
    <?php unset($_SESSION['error']); endif; ?>
    
    <p>Êtes-vous sûr de vouloir supprimer la pétition "<?= htmlspecialchars($petition['Titre']); ?>" ?</p>
    
    <form method="post">
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required><br>
        
        <button type="submit">Supprimer</button>
        <a href="index.php">Annuler</a>
    </form>
</body>
</html>