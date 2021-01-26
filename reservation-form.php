<?php
ob_start();
$page_selected = 'reservation_form';

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Camping - Formulaire de réservation</title>
    <link rel="shortcut icon" type="image/x-icon" href="css/images/logo1.jpg">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style1.css">
</head>
<body>
<header>
    <?php
    include("includes/header.php");
    require 'classes/reservation-form.php';
    $reservation = new reservation($db);
    $connexion = $db->connectDb();
    $infos = new proprietaire($db);
    ?>
</header>
<main>
    <section id="container-resaform">
    <?php
    if (isset($_POST['submit'])) {
        foreach ($_POST as $key => $value) {
            if ($value != 'default') {
                $q = $connexion->prepare("SELECT * FROM types_emplacement WHERE nom_type_emplacement = '$key'");
                $q->execute();
                $result2 = $q->fetch();
                if ($result2 != false) {
                    $type_emplacement_reserve = $result2['nom_type_emplacement'];
                    $nombre_emplacement_reserve = $_POST[$result2['nom_type_emplacement']];
                    $emplacements_reserves[$type_emplacement_reserve] = (int)$nombre_emplacement_reserve;
                }
            }
        }
        if (empty($type_emplacement_reserve)) {
            $errors[] = "Aucun emplacement n'a été choisi.";
            $message = new messages($errors);
            echo $message->renderMessage();
        } else {
            foreach ($emplacements_reserves as $value) {
                if (!isset($nombre_emplacements_total)) {
                    $nombre_emplacements_total = 0;
                }
                $nombre_emplacements_total = $nombre_emplacements_total + $value;
            }
        }
        if (empty($errors)) {
            $reservation_possible = $reservation->checkReservation(
                $_POST['lieu'],
                $_POST['arrival'],
                $_POST['departure'],
                $nombre_emplacements_total
            );

            if ($reservation_possible) {
                $arrival = $_POST['arrival'];
                $departure = $_POST['departure'];
                $lieu = $_POST['lieu'];

                // INSERTION TABLE reservation
                $request1 = $connexion->prepare(
                    "INSERT INTO reservations (date_debut, date_fin, id_utilisateur) VALUES (:date_debut, :date_fin, :id_utilisateur)"
                );
                $request1->bindParam(':date_debut', $arrival, PDO::PARAM_STR);
                $request1->bindParam(':date_fin', $departure, PDO::PARAM_STR);
                $request1->bindParam(':id_utilisateur', $_SESSION['user']['id_user'], PDO::PARAM_INT);
                $request1->execute();
                $idresa = $connexion->lastInsertId();

                // INSERTION TABLE detail_lieux
                foreach ($infos->getLieux() as $value) {
                    if ($value['nom_lieu'] == $lieu) {
                        $q2 = $connexion->prepare("SELECT prix_journalier FROM lieux WHERE nom_lieu= :lieu");
                        $q2->bindParam(':lieu', $lieu, PDO::PARAM_STR);
                        $q2->execute();
                        $prix_journalier = $q2->fetch();
                    }
                }
                $request2 = $connexion->prepare(
                    "INSERT INTO detail_lieux (nom_lieu, prix_journalier, id_reservation) VALUES (:nom_lieu, :prix_journalier, :id_reservation)"
                );
                $request2->bindParam(':nom_lieu', $lieu, PDO::PARAM_STR);
                $request2->bindParam(':prix_journalier', $prix_journalier['prix_journalier'], PDO::PARAM_STR);
                $request2->bindParam(':id_reservation', $idresa, PDO::PARAM_INT);
                $request2->execute();

                // INSERTION TABLE detail_types_emplacement
                foreach ($emplacements_reserves as $type_emplacement => $nombre_emplacement) {
                    echo $nombre_emplacement;
                    $request3 = $connexion->prepare(
                        "INSERT INTO detail_types_emplacement (nom_type_emplacement, nb_emplacements_reserves, id_reservation) VALUES (:nom_type_emplacement,:nb_emplacements_reserves, :id_reservation)"
                    );
                    $request3->bindParam(':nom_type_emplacement', $type_emplacement, PDO::PARAM_STR);
                    $request3->bindParam(':nb_emplacements_reserves', $nombre_emplacement, PDO::PARAM_INT);
                    $request3->bindParam(':id_reservation', $idresa, PDO::PARAM_INT);
                    $request3->execute();
                    
                }

                if (empty($_POST['option'])){
                    $null = ('sans option');
                    $request_op = $connexion->prepare(
                        "INSERT INTO detail_options (nom_option, prix_option, id_reservation) VALUES (:nom_option,:prix_option,:id_reservation)"
                    );
                    $request_op->bindParam(':nom_option',$null, PDO::PARAM_STR);
                    $request_op->bindParam(':prix_option',$value = 0, PDO::PARAM_INT);
                    $request_op->bindParam(':id_reservation', $idresa, PDO::PARAM_INT);
                    $request_op->execute();
                }else{

                    foreach ($_POST['option'] as $name_option) {
                    $q3 = $connexion->prepare("SELECT * FROM options WHERE nom_option = :nom_option");
                    $q3->bindParam(':nom_option', $name_option);
                    $q3->execute();
                    $options = $q3->fetch();
                    $request5 = $connexion->prepare(
                        "INSERT INTO detail_options (nom_option, prix_option, id_reservation) VALUES (:nom_option,:prix_option,:id_reservation)"
                    );
                    $request5->bindParam(':nom_option', $name_option, PDO::PARAM_STR);
                    $request5->bindParam(':prix_option', $options['prix_option'], PDO::PARAM_INT);
                    $request5->bindParam(':id_reservation', $idresa, PDO::PARAM_INT);
                    $request5->execute();
                    }
                }
                // INSERTION TABLE prix_detail

                //RECUPERE nombre de jours réservés
                $nb_days = $connexion->prepare(
                    "SELECT DATEDIFF(date_fin, date_debut) FROM reservations WHERE id_reservation = ?"
                );
                $nb_days->execute([$idresa]);
                $nb_days = $nb_days->fetchAll();
                $days = $nb_days[0][0] + 1;

                //RECUPERE nombre total d'emplacements réservés
                $emplacement = $connexion->prepare(
                    "SELECT SUM(nb_emplacements_reserves) FROM detail_types_emplacement WHERE id_reservation = ?"
                );
                $emplacement->execute([$idresa]);
                $emplacement = ($emplacement->fetchAll());
                $nb_emp = $emplacement[0][0];

                //RECUPERE prix journalier multiplié par le nb d'emplacements
                $lieux = $connexion->prepare(
                    "SELECT prix_journalier * $nb_emp FROM detail_lieux WHERE id_reservation = ?"
                );
                $lieux->execute([$idresa]);
                $lieux = $lieux->fetchAll();
                $price_day = $lieux[0][0];

                //RECUPERE prix total des options choisies
                $option = $connexion->prepare("SELECT SUM(prix_option) FROM detail_options WHERE id_reservation = ?");
                $option->execute([$idresa]);
                $option = ($option->fetchAll());
                $price_option = $option[0][0];

                //FONCTION cacul prix réservation
                function facture($nb_jours, $operation, $total_jour)
                {
                    $calcul = $nb_jours * $total_jour;
                    return $calcul;
                }

                $resultat = facture($nb_jours = $days, $operation = '*', $total_jour = ($price_day + $price_option));

                //INSERTION finale
                $request_total = $connexion->prepare(
                    "INSERT INTO prix_detail (nb_emplacement, prix_journalier, prix_options, nb_jours, prix_total, id_reservation) VALUES (:nb_emplacement,:prix_journalier,:prix_options,:nb_jours, :prix_total, :id_reservation)"
                );
                $request_total->bindParam(':nb_emplacement', $nb_emp, PDO::PARAM_INT);
                $request_total->bindParam(':prix_journalier', $price_day, PDO::PARAM_STR);
                $request_total->bindParam(':prix_options', $price_option, PDO::PARAM_STR);
                $request_total->bindParam(':nb_jours', $days, PDO::PARAM_INT);
                $request_total->bindParam(':prix_total', $resultat, PDO::PARAM_STR);
                $request_total->bindParam(':id_reservation', $idresa, PDO::PARAM_INT);
                $request_total->execute();
                header('location:reservation.php?id=' . $idresa . '');
            }
        }
    }
    ?>
    <h1 id="hello">Bienvenue @ <?php
        echo $_SESSION['user']['firstname'] ?>!<br>Réservez votre séjour maintenant</h1>
    <?php
    if (!isset($_POST['Valider_date_lieu']) and empty($_POST)) {
        ?>
        <section id="sub-form">
            <form id="form_date_lieu" method="post" action="reservation-form.php">
                <section id="date-section">
                    <label for="arrival">Date d'arrivée</label>
                    <input type="date" name="arrival">
                    <label for="departure">Date de départ</label>
                    <input type="date" name="departure" id="departure">
                    </br><i>* Les réservations correspondent aux nuits</i>
                </section>
                <section id="section-lieu">
                    <select name="lieu" id="" name="">
                        <optgroup label="Choix du lieu">
                            <option value="default" selected hidden>--Sélectionnez votre lieu--</option>
                            <?php
                            foreach ($infos->getLieux() as $lieu) { ?>
                            <option value="<?= $lieu['nom_lieu'] ?>"><?= $lieu['nom_lieu'] ?></option>
                            <?php
                            } ?>
                        </optgroup>
                    </select>
                    <button type="submit" name="Valider_date_lieu">Valider</button>
                </section>
            </form>
        <?php
        } else {
        if (!empty($_POST['arrival']) and !empty($_POST['departure']) and !empty($_POST['lieu'])) {
            $_SESSION['arrival'] = $_POST['arrival'];
            $_SESSION['departure'] = $_POST['departure'];
            $_SESSION['nom_lieu'] = $_POST['lieu'];
        ?>
        <section id="sub-form1">
            <form method="post" action="reservation-form.php">
                <section id="date-section1">
                    <label for="arrival">Date d'arrivée</label>
                    <input id="arrival" type="date" name="arrival" value="<?= $_SESSION['arrival'] ?>" readonly>
                    <label for="departure">Date de départ</label>
                    <input id="departure" type="date" name="departure" value="<?= $_SESSION['departure'] ?>" readonly>
                    <label for="lieu">Lieu</label>
                    <input id="lieu" type="text" name="lieu" value="<?= $_SESSION['nom_lieu'] ?>" readonly>
                </section>
                <section id="type-section">
                    <h2>Choisissez votre type d'emplacement</h2>
                    <?php
                    foreach ($infos->getLieux() as $lieu) { ?>
                        <?php
                        if ($lieu['nom_lieu'] == $_SESSION['nom_lieu']) {
                            $test = $lieu['emplacements_disponibles'];
                        }
                    } ?>
                    <?php
                    foreach ($infos->getTypesEmplacement() as $type_emplacement) {
                        $test2 = $type_emplacement['nb_emplacements'];
                        $result = intval($test / $test2);
                        if ($result >= 1) {
                            ?>
                            <select name="<?= $type_emplacement['nom_type_emplacement'] ?>">
                                <option value="default">0 <?= $type_emplacement['nom_type_emplacement'] ?></option>
                                <?php
                                for (
                                    $i = 1;
                                    $i <= $result;
                                    $i++
                                ) {
                                    $nom_emplacement = $type_emplacement['nom_type_emplacement'];
                                    if (!isset($total_emplacement)) {
                                        $total_emplacement = 0;
                                    }
                                    echo $total_emplacement = $i * (int)$type_emplacement['nb_emplacements'];
                                    ?>
                                    <option value="<?= $total_emplacement ?>"><?php
                                        echo "$i $nom_emplacement" ?></option>
                                    <?php
                                } ?>
                            </select>
                            <?php
                        }
                    } ?>
                </section>
                <section id="option-section">
                    <h2>Choisissez vos options durant votre séjour</h2>
                    <?php
                    foreach ($infos->getOptions() as $option) { ?>
                        <section><input type="checkbox" id="<?= $option['id_option'] ?>" name="option[]"
                               value="<?= $option['nom_option'] ?>">
                        <label for="<?= $option['id_option'] ?>"><?= $option['nom_option'] ?>
                            à <?= $option['prix_option'] ?>
                            €/jour</label>
                        </section>
                        <?php
                    } ?>
                </section>
                <section id="but-form"><button id="form-button" type="submit" name="submit">Réserver</button></section>
            </form>
        </section>
            <?php
        } else {
            $errors[] = "Tous les éléments doivent être seléctionnés.";
            $message = new messages($errors);
            echo $message->renderMessage();
        }
    } ?>
      </section>
    </section>
</main>
<footer>
    <?php
    include("includes/footer.php");
    ob_end_flush();
    ?>
</footer>
</body>
</html>