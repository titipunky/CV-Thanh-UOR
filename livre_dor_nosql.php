<?php

// Nom ....... : livre_dor.php
// Rôle ...... : Formulaire avec PHP qui va recolter et afficher les donnees (Version NoSQL)
// Auteur .... : Thien Thanh NGUYEN
// Licence ... : Réalisé dans le cadre du chapitre 5 exercice 5.1 de l'UOR

//nom du fichier qui fera office de base de données NoSQL
$fichier_db = 'messages.json';

//si le fichier n'existe pas, on le crée avec un tableau vide
if (!file_exists($fichier_db)) {
    file_put_contents($fichier_db, json_encode([]));
}

//fonction de nettoyage du cours p137
function nettoyer($x) {
    if (isset($x)) {
        $x = trim($x);
        $x = stripslashes($x);
        $x = htmlspecialchars($x);
        return $x;
    }
}

//si le formulaire a été envoyé on vérifie si le champ 'nom' existe
if (isset($_POST['nom'])) {
    
    //on nettoie les données
    $nom = nettoyer($_POST['nom']);
    $prenom = nettoyer($_POST['prenom']);
    $pseudo_github = nettoyer($_POST['pseudo_github']);
    $date_anniversaire = nettoyer($_POST['date_anniversaire']);
    $note = nettoyer($_POST['note']);
    $message = nettoyer($_POST['message']);
    
    //on génère la date du jour avec PHP
    $date_visite = date('Y-m-d');

    //on crée un tableau associatif représentant notre nouvelle entree
    $nouvel_avis = array(
        'nom' => $nom,
        'prenom' => $prenom,
        'pseudo_github' => $pseudo_github,
        'date_anniversaire' => $date_anniversaire,
        'note' => $note,
        'message' => $message,
        'date_visite' => $date_visite
    );

    //on récupère le contenu actuel du fichier JSON
    $donnees_existantes = json_decode(file_get_contents($fichier_db), true);
    
    //on ajoute le nouvel avis à la fin du tableau
    $donnees_existantes[] = $nouvel_avis;
    
    //on réécrit le fichier JSON avec les nouvelles données
    file_put_contents($fichier_db, json_encode($donnees_existantes, JSON_PRETTY_PRINT));

    //message de confirmation
    echo "<h3 style='color: green; text-align: center;'>Merci " . $prenom . ", ton message a bien été enregistré !</h3>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Livre d'or</title>
    <style>
        /*CSS pour que le formulaire soit centré et lisible */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { background-color: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: blueviolet; } /* rappel de mon code couleur du CV ! */
        label { font-weight: bold; margin-top: 10px; display: block; }
        input[type="text"], input[type="date"], textarea { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;}
        input[type="submit"] { background-color: #0056b3; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-weight: bold; }
        input[type="submit"]:hover { background-color: blueviolet; }
        .avis { border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Laissez votre avis !</h2>

    <form action="" method="POST"> 
        
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="pseudo_github">Pseudo GitHub (optionnel) :</label>
        <input type="text" id="pseudo_github" name="pseudo_github">

        <label for="date_anniversaire">Date de naissance :</label>
        <input type="date" id="date_anniversaire" name="date_anniversaire">

        <label>Notez mon travail (sur 5) :</label>
        <input type="radio" id="note1" name="note" value="1" required> <label for="note1" style="display:inline; font-weight:normal;">1</label>
        <input type="radio" id="note2" name="note" value="2"> <label for="note2" style="display:inline; font-weight:normal;">2</label>
        <input type="radio" id="note3" name="note" value="3"> <label for="note3" style="display:inline; font-weight:normal;">3</label>
        <input type="radio" id="note4" name="note" value="4"> <label for="note4" style="display:inline; font-weight:normal;">4</label>
        <input type="radio" id="note5" name="note" value="5"> <label for="note5" style="display:inline; font-weight:normal;">5</label>
        <br><br>

        <label for="message">Votre message :</label>
        <textarea id="message" name="message" rows="4" required></textarea>

        <input type="submit" value="Envoyer">
    </form>
    
<hr>
    <h3>Les avis de mes visiteurs :</h3>

    <?php
    //affichage des messages "NoSQL"
    
    //on récupère toutes les données du fichier
    $tous_les_messages = json_decode(file_get_contents($fichier_db), true);
    
    if (!empty($tous_les_messages)) {
        //on inverse le tableau pour avoir les plus récents en premier (équivalent de ORDER BY id DESC)
        $tous_les_messages = array_reverse($tous_les_messages);
        
        //on garde uniquement les 10 premiers (équivalent de LIMIT 0, 10)
        $dix_derniers_messages = array_slice($tous_les_messages, 0, 10);

        //boucle pour afficher chaque message
        foreach ($dix_derniers_messages as $donnees) {
    ?>
            <div class="avis">
                <p><strong><?php echo htmlspecialchars($donnees['prenom']); ?></strong> a mis la note de <?php echo htmlspecialchars($donnees['note']); ?>/5</p>
                <p><?php echo nl2br(htmlspecialchars($donnees['message'])); ?></p>
                <p style="font-size: 0.8em;"><em>Posté le : <?php echo htmlspecialchars($donnees['date_visite']); ?></em></p>
            </div>
    <?php
        }
    } else {
        echo "<p>Aucun avis pour le moment. Soyez le premier !</p>";
    }
    ?>

    <br><a href="index.html">Retour au CV</a>
</div>
</body>
</html>