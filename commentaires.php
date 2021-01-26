<?php 

//Ajout d'un commentaire vérifications

function addComment($note, $titre, $avis, $id_user, $id_reservation)
{
    $db = new Database();
    $connexion = $db->connectDb();

    //verification unique
    $q = $connexion->prepare("SELECT id_reservation FROM avis WHERE id_reservation = :id_reservation");
    $q->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
    $q->execute();
    $avis_existant = $q->fetch();
    if (!empty($avis_existant)){
        $errors[] = "Vous avez déjà laissé un avis pour ce séjour !";
    }

    //verification note
    if ($note < 0 or $note > 5) {
        $errors[] = "La note doit être comprise entre 0 et 5";
    }
    //verification titre
    if (strlen($avis) < 3 or strlen($titre) > 50) {
        $errors[] = "Le titre doit comporter entre 3 et 50 caractères";
    }
    //veridication avis
    if (strlen($avis) < 10 or strlen($avis) > 500) {
        $errors[] = "L'avis doit comporter entre 10 et 500 caractères";
    }
    if (empty($errors)) {
        $date = date('Y-m-d');
        $q = $connexion->prepare(
            "INSERT INTO avis (note_sejour, titre_avis, texte_avis, post_date, id_utilisateur, id_reservation) VALUES (:note_sejour, :titre_avis, :texte_avis, :post_date, :id_utilisateur, :id_reservation)"
        );
        $q->bindParam(':note_sejour', $note, PDO::PARAM_INT);
        $q->bindParam(':titre_avis', $titre, PDO::PARAM_STR);
        $q->bindParam(':texte_avis', $avis, PDO::PARAM_STR);
        $q->bindParam(':post_date', $date, PDO::PARAM_STR);
        $q->bindParam(':id_utilisateur', $id_user, PDO::PARAM_INT);
        $q->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
        $q->execute();
    } else {
        $message = new messages($errors);
        echo $message->renderMessage();
    }
}

function viewComment($id_avis)
{
    $db = new Database();
    $connexion = $db->connectDb();
    //récupération avis
    $q = $connexion->prepare("SELECT * FROM avis WHERE id_avis = :id_avis");
    $q->bindParam(':id_avis', $id_avis, PDO::PARAM_INT);
    $q->execute();
    $avis = $q->fetch();
    //récupération réservation
    $reservation = $avis['id_reservation'];
    $q2 = $connexion->prepare(
        "SELECT reservations.date_debut,
                reservations.date_fin,
                utilisateurs.nom,
                utilisateurs.prenom,
                utilisateurs.avatar
                FROM reservations, utilisateurs
                WHERE reservations.id_reservation = :reservation
                AND utilisateurs.id_utilisateur = reservations.id_utilisateur"
    );
    $q2->bindParam(':reservation', $reservation, PDO::PARAM_INT);
    $q2->execute();
    $other_infos_from_reservation = $q2->fetch();
    ?>
    <div class="avis">
        <div class="avis_utilisateur"> <!-- utilisateur -->
            <img src="<?= $other_infos_from_reservation['avatar'] ?>"
                 alt="Avatar de <?= $other_infos_from_reservation['prenom'] ?>"
                 width='30' height='30'>
            <p><?= $other_infos_from_reservation['prenom'] ?></p>
        </div>
        <div class="avis_texte"><!-- avis -->
            <div class="note_avis">
                <p><?= $avis['notre_sejour'] ?></p>
                <p class="date_avis">Avis publié le <?= date('d-m-Y',strtotime($avis['post_date'])) ?></p>
            </div>
            <p class="titre_avis"><?= $avis['titre_avis'] ?></p>
            <p class="avis_texte_quote"><i class="fas fa-angle-double-left"></i> <?= $avis['texte_avis'] ?> <i class="fas fa-angle-double-right"></i></p>
            <p class="date_avis">A séjourné du <?= date('d-m-Y',strtotime($other_infos_from_reservation['date_debut'])) ?>
                au <?= date('d-m-Y',strtotime($other_infos_from_reservation['date_fin'])) ?></p>
        </div>
    </div>
    <?php
}




