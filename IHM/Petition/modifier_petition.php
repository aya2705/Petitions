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
        
        $data = [
            'titre' => $_POST['titre'],
            'description' => $_POST['description'],
            'dateFinP' => $_POST['dateFinP'],
            'porteurP' => $_POST['porteurP'],
            'email' => $_POST['email'],
        ];
        
        if (Petition::updatePetition($id, $data)) {
            
            $petitions = Petition::getAllPetitions();
            $_SESSION['petitions'] = $petitions;

            $_SESSION['message'] = "Pétition modifiée avec succès.";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Erreur lors de la modification de la pétition.";
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
    <title>Modifier la Pétition</title>
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
        }
        h1 {
            text-align: center;
        }
        label {
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background: #218838;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Modifier la Pétition</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div style="color: red;"><?= $_SESSION['error']; ?></div>
    <?php unset($_SESSION['error']); endif; ?>
    
    <form method="post">
        <label for="titre">Titre :</label>
        <input type="text" name="titre" value="<?= htmlspecialchars($petition['Titre']); ?>" required><br>
        
        <label for="description">Description :</label>
        <textarea name="description" required><?= htmlspecialchars($petition['Description']); ?></textarea><br>
        
        <label for="dateFinP">Date de Fin :</label>
        <input type="date" name="dateFinP" value="<?= htmlspecialchars($petition['DateFinP']); ?>" required><br>
        
        <label for="porteurP">Porteur :</label>
        <input type="text" name="porteurP" value="<?= htmlspecialchars($petition['PorteurP']); ?>" required><br>
        
        <label for="email">Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($petition['Email']); ?>" required><br>
        
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required><br>
        
        <button type="submit">Modifier</button>
    </form>
</body>
</html>