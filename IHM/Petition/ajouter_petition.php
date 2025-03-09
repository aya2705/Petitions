
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Pétition</title>
</head>
<body>
    <h1>Ajouter une Pétition</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div style="color: red;"><?= $_SESSION['error']; ?></div>
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
        
        <button type="submit">Ajouter</button>
    </form>
    <br>
    <a href="index.php">Retour à la liste</a>
</body>
</html>