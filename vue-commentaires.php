<?php
ob_start();
$page_selected = 'voir_avis';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Camping - Avis</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes"/>
    <link rel="shortcut icon" type="image/x-icon" href="css/images/logo1.jpg">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style1.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<header>
    <?php
    include 'includes/header.php';
    $connexion = $db->connectDb();
    include 'commentaires.php';
    ?>
</header>
<main>

    <?php
    //AJOUTER AVIS SI DISPO

    //REQUETE infos table reservations
    $q2 = $connexion->prepare("SELECT * FROM reservations WHERE id_utilisateur = :id_user");
    $q2->bindParam(':id_user', $_SESSION['user']['id_user'], PDO::PARAM_INT);
    $q2->execute();
    $reservations_infos = $q2->fetchAll();

    //REQUETE id_reservation dans avis
    $q3 = $connexion->prepare("SELECT id_reservation FROM avis WHERE id_utilisateur = :id_user");
    $q3->bindParam(':id_user', $_SESSION['user']['id_user'], PDO::PARAM_INT);
    $q3->execute();
    $reservations_already_rated = $q3->fetchAll();
    foreach ($reservations_already_rated as $key => $value) {
        $reservations_already_rated[$key] = $value['id_reservation'];
    }
    //TABLEAU reservations pour lesquelles il n'y a pas d'avis
    foreach ($reservations_infos as $reservation_info) {
        $result = array_intersect($reservation_info, $reservations_already_rated);
        if (empty($result)) {
            $reservation_without_rate[$reservation_info['id_reservation']] = [
                'date_debut' =>
                    $reservation_info['date_debut'],
                'date_fin' =>
                    $reservation_info['date_fin']
            ];
        }
    }
    if (isset ($_SESSION['user']['id_user']) and isset($reservation_without_rate)) {
        ?>
        <p id="redirection_form_avis">Nous détectons que vous avez séjourné chez nous mais vous n'avez pas encore laissé d'avis, <a href="#avis_form">souhaitez-vous commenter votre séjour ?</a></p>
        <?php
    }

    //PAGINATION
    // On détermine sur quelle page on se trouve
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $currentPage = (int)strip_tags($_GET['page']);
    } else {
        $currentPage = 1;
    }
    $q4 = $connexion->prepare("SELECT COUNT(*) AS nb_avis FROM avis;");
    $q4->execute();
    $result_q4 = $q4->fetch();
    $nombre_avis = (int)$result_q4['nb_avis'];
    // On détermine le nombre d'articles par page
    $parPage = 10;
    // On calcule le nombre de pages total
    $pages = ceil($nombre_avis / $parPage);
    // Calcul du 1er article de la page
    $premier = ($currentPage * $parPage) - $parPage;

    // AFFICHER AVIS
    $q = $connexion->prepare("SELECT id_avis FROM avis ORDER BY id_avis DESC LIMIT :premier, :parpage");
    $q->bindParam(':premier', $premier, PDO::PARAM_INT);
    $q->bindParam(':parpage', $parPage, PDO::PARAM_INT);
    $q->execute();
    $avis = $q->fetchAll();
    foreach ($avis as $value) {
        viewComment($value['id_avis']);
    }
    ?>
    <nav>
        <ul class="pagination">
            <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
            <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                <a href="vue-commentaires.php?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
            </li>
            <?php
            for ($page = 1; $page <= $pages; $page++): ?>
                <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                    <a href="vue-commentaires.php?page=<?= $page ?>" class="page-link"><?= $page ?></a>
                </li>
            <?php
            endfor ?>
            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
            <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                <a href="vue-commentaires.php?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
            </li>
        </ul>
    </nav>
    <?php

    if (isset($_POST['ajouter_avis'])) {
        if (!empty($_POST['note_avis']) and !empty($_POST['titre_avis']) and !empty($_POST['texte_avis']) and !empty($_POST['reservation'])) {
            $note_avis = $_POST['note_avis'];
            $titre_avis = htmlspecialchars($_POST['titre_avis']);
            $texte_avis = htmlspecialchars($_POST['texte_avis']);
            $id_reservation = $_POST['reservation'];
            $id_user = $_SESSION['user']['id_user'];
            addComment($note_avis, $titre_avis, $texte_avis, $id_user, $id_reservation);
            header('location:vue-commentaires.php');
        } else {
            $errors[] = "Veuillez remplir tous les champs";
        }
    }
    if (isset ($_SESSION['user']['id_user']) and isset($reservation_without_rate)) {
        ?>
        <section id="avis_form">
            <h1>Commentez votre séjour :</h1>
            <section id="sub_form_2">
                <form action="vue-commentaires.php" method="post">
                    <?php
                    if (isset($reservation_without_rate)) {
                        ?>
                        <section id="select_lieu">
                            <h2>Sélectionnez le séjour : </h2>
                            <select name="reservation" id="">
                                <?php
                                foreach ($reservation_without_rate as $key => $value) { ?>
                                    <option value="<?= $key ?>">Séjour du <?= $value['date_debut'] ?>
                                        au <?= $value['date_fin'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </section>
                        <br>
                        <section id="note_sejour">
                            <label for="note_avis"><h2>Notez votre séjour : <?= '&nbsp'; ?></h2></label>
                            <p><input id="note_avis" name="note_avis" type="number" min="0" max="5"><?= '&nbsp'; ?>/5
                            </p>
                        </section>
                        <section id="texte_avis">
                            <label for="titre_avis"><h2>Titre</h2></label>
                            <input type="text" name="titre_avis" minlength="3" maxlength="50"
                                   placeholder="Ajoutez un titre à votre commentaire">
                            <br>
                            <h2>Décrivez votre séjour ...</h2>
                            <textarea name="texte_avis" id="texte_avis" minlength="10" maxlength="500" cols="30"
                                      rows="10"
                                      placeholder="Commentez votre séjour..."></textarea>
                            <button id="avis_button" name="ajouter_avis">Envoyer</button>
                        </section>

                        <?php
                    }
                    ?>
                </form>
            </section>
        </section>
        <?php
    } ?>
</main>
<footer>
    <?php
    include("includes/footer.php");
    ob_end_flush();
    ?>
</footer>
</body>
</html>