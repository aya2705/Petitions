<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Pétitions</title>
    <style>
        .notification {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
        }
        .top-petition {
            background-color: #f4f4f4;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .updated {
            animation: highlightCell 1.5s ease-in-out;
        }
        @keyframes highlightCell {
            0% { background-color: #ffffff; }
            50% { background-color: #ffffcc; }
            100% { background-color: #ffffff; }
        }
        /* Add new styles to match other styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Liste des Pétitions</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
    <div style="color: green;"><?= $_SESSION['message']; ?></div>
    <?php unset($_SESSION['message']); endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div style="color: red;"><?= $_SESSION['error']; ?></div>
    <?php unset($_SESSION['error']); endif; ?>
    
    <!-- Top Petition Section -->
    <h2>📢 Pétition la plus signée</h2>
    <div class="top-petition">
        <strong>Titre :</strong> <span id="top-title">Chargement...</span> <br>
        <strong>Nombre de signatures :</strong> <span id="top-signatures">0</span>
    </div>
    
    <table border="1">
        <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Date de Publication</th>
            <th>Date de Fin</th>
            <th>Porteur</th>
            <th>Email</th>
            <th>Signatures</th>
            <th>Action</th>
        </tr>
        <?php if (isset($_SESSION['petitions'])): ?>
            <?php foreach ($_SESSION['petitions'] as $row): ?>
            <tr class="petition-row" data-id="<?= $row['IDP']; ?>">
                <td><?= htmlspecialchars($row['Titre']); ?></td>
                <td><?= htmlspecialchars($row['Description']); ?></td>
                <td><?= htmlspecialchars($row['DatePublic']); ?></td>
                <td><?= htmlspecialchars($row['DateFinP']); ?></td>
                <td><?= htmlspecialchars($row['PorteurP']); ?></td>
                <td><?= htmlspecialchars($row['Email']); ?></td>
                <td class="signature-count"><?= htmlspecialchars($row['signature_count'] ?? 0); ?></td> 
                <td>
                    <a href="../../Traitement/Utilisateurs.php?action=sign&id=<?= $row['IDP']; ?>">Signer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
    <br>
    <a href="ajouter_petition.php">Ajouter une pétition</a>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let lastPetitionId = 0;

        function loadLastPetitionId() {
            $.ajax({
                url: "../../Traitement/AjaxController.php?action=check_new_petition",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.id !== null) {
                        lastPetitionId = response.id;
                    }
                }
            });
        }

        function checkNewPetition() {
            $.ajax({
                url: "../../Traitement/AjaxController.php?action=check_new_petition",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.id !== null && response.id > lastPetitionId) {
                        lastPetitionId = response.id;

                        // Vérifie si cette pétition est déjà dans le tableau
                        if ($('.petition-row[data-id="'+response.id+'"]').length === 0) {
                            // Ajouter une nouvelle ligne dans le tableau des pétitions
                            let newRow = `
                                <tr class="petition-row" data-id="${response.id}">
                                    <td>${response.titre}</td>
                                    <td>${response.description}</td>
                                    <td>${response.date_public}</td>
                                    <td>${response.date_fin}</td>
                                    <td>${response.porteur}</td>
                                    <td>${response.email}</td>
                                    <td class="signature-count">${response.signature_count || 0}</td>
                                    <td>
                                        <a href="../../Traitement/Utilisateurs.php?action=sign&id=${response.id}">Signer</a>
                                    </td>
                                </tr>
                            `;
                            $("table").append(newRow);

                            // Afficher une notification
                            let notification = document.createElement("div");
                            notification.innerHTML = `📢 Nouvelle pétition ajoutée : <b>${response.titre}</b> par <b>${response.porteur}</b>`;
                            notification.classList.add("notification");
                            document.body.appendChild(notification);

                            // Supprimer la notification après 5 secondes
                            setTimeout(() => {
                                notification.remove();
                            }, 5000);
                        }
                    }
                }
            });
        }

        function updateTopPetition() {
            $.ajax({
                url: "../../Traitement/AjaxController.php?action=top_petition",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    $("#top-title").text(response.Titre);
                    $("#top-signatures").text(response.nombre_signatures);
                }
            });
        }

        function updateSignatureCounts() {
            // Collect all petition IDs from the table
            let petitionIds = [];
            $('.petition-row').each(function() {
                petitionIds.push($(this).data('id'));
            });
            
            // If no petitions on page, don't make the request
            if (petitionIds.length === 0) return;
            
            $.ajax({
                url: "../../Traitement/AjaxController.php?action=get_signature_counts&ids=" + petitionIds.join(','),
                method: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success && response.counts) {
                        // Update each petition's signature count
                        for (const [id, count] of Object.entries(response.counts)) {
                            const cell = $(`.petition-row[data-id="${id}"] .signature-count`);
                            const currentCount = parseInt(cell.text());
                            
                            // Only update if the count changed
                            if (currentCount !== count) {
                                cell.text(count);
                                
                                // Animation to highlight the change
                                cell.addClass('updated');
                                setTimeout(() => {
                                    cell.removeClass('updated');
                                }, 1500);
                            }
                        }
                    }
                }
            });
        }

        $(document).ready(function () {
            loadLastPetitionId();
            updateTopPetition();
            
            // Vérifier pour nouvelles pétitions toutes les 5 secondes
            setInterval(checkNewPetition, 5000);
            
            // Mettre à jour la pétition la plus signée toutes les 5 secondes
            setInterval(updateTopPetition, 5000);
            
            // Update signature counts every 3 seconds
            setInterval(updateSignatureCounts, 3000);
        });
    </script>
</body>
</html>