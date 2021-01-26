<?php


class reservation
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function checkReservation($lieu, $date_debut, $date_fin, $emplacements)
    {
        if (strtotime($date_fin) <= strtotime($date_debut)) {
            $errors[] = "La date de fin doit être supérieure à la date de début.";
        }
        $ajd = strtotime(date('Y-m-d'));
        if (strtotime($date_debut) < $ajd) {
            $errors[] = "Vous ne pouvez pas réserver dans le passé !";
        }
        if ((strtotime($date_fin) - strtotime($date_debut)) > 2419200) {
            $errors[] = "Vous ne pouvez réserver au delà de 4 semaines.";
        }
        $nextyear = mktime(0, 0, 0, date("m"), date("d"), date("Y") + 1);
        if (strtotime($date_debut) > $nextyear) {
            $errors[] = "Nous ne permettons pas de réserver plus d'un an en avance, merci de revenir plus tard.";
        }
        if (empty($errors)) {
            return self::checkDisponibilite($lieu, $date_debut, $date_fin, $emplacements);
        } else {
            $message = new messages($errors);
            echo $message->renderMessage();
        }
    }

    public function CheckDisponibilite($lieu, $date_debut, $date_fin, $emplacements)
    {
        $dates = self::checkDates($lieu, $date_debut, $date_fin);
        if (isset($dates)) {
            foreach ($dates as $key => $value) {
                $prevision = $value - (int)$emplacements;
                if ($prevision < 0) {
                    $jours_non_disponible[] = $key;
                }
                if ($prevision >= 0) {
                    $jours_disponibles[] = $key;
                }
            }

            if (empty($jours_non_disponible)) {
                return true;
            } else {
                $jours_non_disponible = implode(", ", $jours_non_disponible);
                $errors[] = "Les jours suivants ne sont pas disponibles avec votre choix de type d'emplacement : $jours_non_disponible";
                $message = new messages($errors);
                echo $message->renderMessage();
            }
        }
    }

    //Parcours les dates d'une période donnée et vérifie le nombre d'emplacements disponible dans un lieu donné:
    // return un message d'erreur quand aucun emplacement n'est disponible!!
    // return un  tableau quand des jours sont disponibles!!
    public function checkDates($lieu, $date_debut, $date_fin)
    {
        $date_debut_jour = idate('d', strtotime($date_debut));
        $date_debut_mois = idate('m', strtotime($date_debut));
        $date_debut_annee = idate('Y', strtotime($date_debut));

        $date_fin_jour = idate('d', strtotime($date_fin));
        $date_fin_mois = idate('m', strtotime($date_fin));
        $date_fin_annee = idate('Y', strtotime($date_fin));

        $debut_date = mktime(0, 0, 0, $date_debut_mois, $date_debut_jour, $date_debut_annee);
        $fin_date = mktime(0, 0, 0, $date_fin_mois, $date_fin_jour, $date_fin_annee);

        for ($i = $debut_date; $i <= $fin_date; $i += 86400) {
            $date = date("Y-m-d", $i);
            $emplacements_dispo_selon_periode[$date] = self::HowManyEmplacementAvailableForADay($lieu, $date);
        }
        foreach ($emplacements_dispo_selon_periode as $key => $value) {
            if ($value != null) {
                return $emplacements_dispo_selon_periode;
            }
        }
        $date_debut = date('d/m/Y', strtotime($date_debut));
        $date_fin = date('d/m/Y', strtotime($date_fin));
        $errors[] = "Il n'y a aucun emplacement disponible entre le $date_debut et le $date_fin à $lieu";
        $message = new messages($errors);
        echo $message->renderMessage();
    }

    //Donne le nombre d'emplacement disponible pour un jour donné et un lieu donné
    //-> return null si 0 emplacement disponible
    public function HowManyEmplacementAvailableForADay($lieu, $day_check)
    {
        $connexion = $this->db->connectDb();
        $q = $connexion->prepare(
            "SELECT reservations.id_reservation FROM reservations, detail_lieux WHERE reservations.date_debut <= :day_check AND :day_check <= reservations.date_fin AND detail_lieux.nom_lieu = :nom_lieu AND detail_lieux.id_reservation = reservations.id_reservation"
        );
        $q->bindParam(':day_check', $day_check, PDO::PARAM_STR);
        $q->bindParam(':nom_lieu', $lieu, PDO::PARAM_STR);
        $q->execute();
        $reservation_on_same_day = $q->fetchAll();
        foreach ($reservation_on_same_day as $key => $value) {
            $q3 = $connexion->prepare(
                "SELECT nb_emplacements_reserves FROM detail_types_emplacement WHERE id_reservation = :id_reservation"
            );
            $q3->bindParam(':id_reservation', $reservation_on_same_day[$key]['id_reservation'], PDO::PARAM_INT);
            $q3->execute();
            $liste_nb_emplacements_reserves = $q3->fetchAll();
            foreach ($liste_nb_emplacements_reserves as $key => $value) {
                if (!isset($nb_emplacements_reserves_in_a_day)) {
                    $nb_emplacements_reserves_in_a_day = 0;
                }
                $nb_emplacements_reserves_in_a_day = $nb_emplacements_reserves_in_a_day + $liste_nb_emplacements_reserves[$key]['nb_emplacements_reserves'];
            }
        }
        $q2 = $connexion->prepare("SELECT emplacements_disponibles FROM lieux WHERE nom_lieu = :nom_lieu");
        $q2->bindParam(':nom_lieu', $lieu, PDO::PARAM_STR);
        $q2->execute();
        $emplacements_max = $q2->fetch();
        if (!isset($nb_emplacements_reserves_in_a_day)) {
            $nb_emplacements_reserves_in_a_day = 0;
        }
        $emplacements_disponibles = $emplacements_max[0] - $nb_emplacements_reserves_in_a_day;
        if ($emplacements_disponibles == 0) {
            return null;
        } else {
            return $emplacements_disponibles;
        }
    }
}
