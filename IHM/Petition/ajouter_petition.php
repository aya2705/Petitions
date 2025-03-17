<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Pétition</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        form {
            max-width: 500px;
            margin: auto;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"], input[type="email"], input[type="date"], textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Ajouter une Pétition</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div class="error"><?= $_SESSION['error']; ?></div>
    <?php unset($_SESSION['error']); endif; ?>
    
    <form method="post" action="../../Traitement/Utilisateurs.php">
        <input type="hidden" name="action" value="add">
        
        <label for="titre">Titre :</label>
        <input type="text" name="titre" required><br>
        
        <label for="description">Description :</label>
        <textarea name="description" required></textarea><br>
        
        <label for="dateFinP">Date de Fin :</label>
        <input type="date" name="dateFinP" required><br>
        
        <label for="porteurP">Porteur :</label>
        <input type="text" name="porteurP" required><br>
        
        <label for="email">Email :</label>
        <input type="email" name="email" required><br>
        <label for="password">Mot de passe :</label>
<input type="password" name="password" required><br>
        
        <button type="submit">Ajouter</button>
    </form>
    <br>
    <a href="index.php">Retour à la liste</a>
</body>
</html>