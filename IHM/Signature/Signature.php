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
            margin: auto;
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
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        /* New styles for last signatures dashboard */
        .last-signatures {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            max-width: 500px;
            margin: auto;
        }
        .last-signatures h3 {
            margin-top: 0;
            color: #333;
        }
        #lastSignaturesList {
            list-style-type: none;
            padding-left: 0;
        }
        #lastSignaturesList li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        #lastSignaturesList li:last-child {
            border-bottom: none;
        }
        .signature-date {
            color: #777;
            font-size: 0.9em;
        }
        .signature-details {
            font-weight: bold;
        }
        .loading {
            color: #777;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h1>Signer la pétition: <?= htmlspecialchars($petition['Titre']); ?></h1>
    
    <div class="last-signatures">
        <h3>Dernières signatures:</h3>
        <ul id="lastSignaturesList">
            <li class="loading">Chargement des signatures...</li>
        </ul>
    </div>
    
    <?php if (isset($_SESSION['message'])): ?>
    <div class="message success"><?= $_SESSION['message']; ?></div>
    <?php unset($_SESSION['message']); endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div class="error"><?= $_SESSION['error']; ?></div>
    <?php unset($_SESSION['error']); endif; ?>
    
    <form method="post" action="../../Traitement/SignatureController.php" id="signatureForm">
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
    
    <script>
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR', options);
        }
        
        function loadLastSignatures() {
            const petitionId = <?= $petition['IDP']; ?>;
            
            fetch(`../../Traitement/SignatureController.php?action=getLastSignatures&idp=${petitionId}`)
                .then(response => response.json())
                .then(signatures => {
                    const signatureList = document.getElementById("lastSignaturesList");
                    signatureList.innerHTML = ""; 
                    
                    if (signatures.length === 0) {
                        const li = document.createElement("li");
                        li.textContent = "Aucune signature pour l'instant";
                        signatureList.appendChild(li);
                        return;
                    }
                    
                    signatures.forEach(signature => {
                        const li = document.createElement("li");
                        
                        const signatureDetails = document.createElement("div");
                        signatureDetails.className = "signature-details";
                        signatureDetails.textContent = `${signature.Prenom} ${signature.Nom} (${signature.Pays})`;
                        
                        const signatureDate = document.createElement("div");
                        signatureDate.className = "signature-date";
                        signatureDate.textContent = `${formatDate(signature.Date)} à ${signature.Heure.substring(0, 5)}`;
                        
                        li.appendChild(signatureDetails);
                        li.appendChild(signatureDate);
                        
                        signatureList.appendChild(li);
                    });
                })
                .catch(error => {
                    console.error("Error loading signatures:", error);
                    const signatureList = document.getElementById("lastSignaturesList");
                    signatureList.innerHTML = "<li>Erreur lors du chargement des signatures</li>";
                });
        }

        document.addEventListener("DOMContentLoaded", loadLastSignatures);
        
        setInterval(loadLastSignatures, 5000);
        
        document.getElementById("signatureForm").addEventListener("submit", function() {
            
            setTimeout(loadLastSignatures, 1000);
        });
    </script>
</body>
</html>