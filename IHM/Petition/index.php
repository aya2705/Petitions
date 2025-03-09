
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Pétitions</title>
</head>
<body>
    <h1>Liste des Pétitions</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
    <div style="color: green;"><?= $_SESSION['message']; ?></div>
    <?php unset($_SESSION['message']); endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div style="color: red;"><?= $_SESSION['error']; ?></div>
    <?php unset($_SESSION['error']); endif; ?>
    
    <table border="1">
        <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Date de Publication</th>
            <th>Date de Fin</th>
            <th>Porteur</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php if (isset($_SESSION['petitions'])): ?>
            <?php foreach ($_SESSION['petitions'] as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['Titre']); ?></td>
                <td><?= htmlspecialchars($row['Description']); ?></td>
                <td><?= htmlspecialchars($row['DatePublic']); ?></td>
                <td><?= htmlspecialchars($row['DateFinP']); ?></td>
                <td><?= htmlspecialchars($row['PorteurP']); ?></td>
                <td><?= htmlspecialchars($row['Email']); ?></td>
                <td>
                    <a href="../../Traitement/Utilisateurs.php?action=sign&id=<?= $row['IDP']; ?>">Signer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
    <br>
    <a href="ajouter_petition.php">Ajouter une pétition</a>
</body>
</html>