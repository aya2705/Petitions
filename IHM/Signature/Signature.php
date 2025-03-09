<?php
session_start();
if (!isset($_SESSION['petition'])) {
    header('Location: ../../Traitement/Utilisateurs.php');
    exit();
}
$petition = $_SESSION['petition'];
$signatureCount = $_SESSION['signatureCount'] ?? 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signer une Pétition</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        .signature-count {
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        form {
            max-width: 500px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"], input[type="email"] {
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
    <h1>Signer la pétition: <?= htmlspecialchars($petition['Titre']); ?></h1>
    
    <!-- Display signature count -->
    <div class="signature-count">
        <p><strong>Nombre de signatures actuelles:</strong> <?= $signatureCount ?></p>
    </div>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div class="error"><?= $_SESSION['error']; ?></div>
    <?php unset($_SESSION['error']); endif; ?>
    
    <form method="post" action="../../Traitement/SignatureController.php">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="idp" value="<?= $petition['IDP']; ?>">
        
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required>
        
        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" required>
        
        <label for="pays">Pays :</label>
        <input type="text" name="pays" id="pays" required>
        
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required>
        
        <button type="submit">Signer</button>
    </form>
    <br>
    <a href="../../Traitement/Utilisateurs.php">Retour à la liste</a>
</body>
</html>