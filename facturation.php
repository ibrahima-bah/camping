<?php
ob_start();
$page_selected = 'gestion_reservation';
?>

<!DOCTYPE html>
<html>

<head>
    <title>camping - gestion_reservation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/x-icon" href="css/images/logo1.jpg">
    <link rel="stylesheet" type="text/css" href="css/style1.css">
</head>

<body>
    <header>
        <?php 
        include("includes/header.php"); 
        require 'classes/reservation-form.php';
        $modif_reservation = new reservation($db);
        $connexion = $db->connectDb();
        $infos = new proprietaire($db);
        ?>
    </header>
    <main class="main_gestion_reservation">
        <?php
        try
        {
            //CONNEXION BDD
            $connexion1=new PDO("mysql:host=localhost;dbname=camping",'root','');
            // DEFINITION MODE D'ERREUR PDO SUR EXCEPTION
            $connexion1->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
            //VARIABLE STOCKANT L'ID DE RESERVATION
            $id_booking= $_GET['id_reservation'];
            
            
            //RECUPERATION DES INFORMATIONS LIEES A LA RESERVATION
            $info_customer = $connexion1->prepare ("SELECT reservations.id_utilisateur, utilisateurs.id_utilisateur, utilisateurs.nom, utilisateurs.prenom, utilisateurs.num_tel, utilisateurs.email FROM reservations, utilisateurs WHERE reservations.id_reservation = $id_booking AND reservations.id_utilisateur = utilisateurs.id_utilisateur ");
            //EXECUTION REQUETE
            $info_customer->execute();
            //RECUPERATION RESULTAT
            $resultat_info_customer = $info_customer->fetchAll(PDO::FETCH_ASSOC);
            
            
            //RECUPERATION DES INFORMATIONS LIEES A LA RESERVATION
            $info_reservation = $connexion1->prepare ("SELECT * FROM reservations WHERE id_reservation = $id_booking");
            //EXECUTION REQUETE
            $info_reservation->execute();
            //RECUPERATION RESULTAT
            $resultat_info_reservation = $info_reservation->fetchAll(PDO::FETCH_ASSOC);

            //RECUPERATION DES INFORMATIONS LIEES A LA RESERVATION
            $info_lieu = $connexion1->prepare ("SELECT * FROM detail_lieux WHERE id_reservation = $id_booking");
            //EXECUTION REQUETE
            $info_lieu->execute();
            //RECUPERATION RESULTAT
            $resultat_info_lieu = $info_lieu->fetchAll(PDO::FETCH_ASSOC);
            
            //RECUPERATION DES INFORMATIONS LIEES A LA RESERVATION
            $info_type = $connexion1->prepare ("SELECT * FROM  detail_types_emplacement WHERE id_reservation = $id_booking");
            //EXECUTION REQUETE
            $info_type->execute();
            //RECUPERATION RESULTAT
            $resultat_info_type = $info_type->fetchAll(PDO::FETCH_ASSOC);
            
            //RECUPERATION DES INFORMATIONS LIEES A LA RESERVATION
            $info_option = $connexion1->prepare ("SELECT * FROM  detail_options WHERE id_reservation = $id_booking");
            //EXECUTION REQUETE
            $info_option->execute();
            //RECUPERATION RESULTAT
            $resultat_info_option = $info_option->fetchAll(PDO::FETCH_ASSOC);
            
            //RECUPERATION DES INFORMATIONS LIEES A LA RESERVATION
            $info_prix = $connexion1->prepare ("SELECT * FROM  prix_detail WHERE id_reservation = $id_booking");
            //EXECUTION REQUETE
            $info_prix->execute();
            //RECUPERATION RESULTAT
            $resultat_info_prix = $info_prix->fetchAll(PDO::FETCH_ASSOC);
        
        }
        
        
        
        catch (PDOException $e) 
        {
        echo "Erreur : " . $e->getMessage();
        }

        ?>
        
        <section class="booking_details">
            <div class="reservation_facture">
           
            <h1>Facture réservation n°<?php echo $resultat_info_reservation[0]['id_reservation'] ?></h1><br/>
            
            <h3>Sardine's Camp</h3><br/>
            <address>
                <p>1 avenue de la Madrague 13008 Marseille</p>
                <a class="contact" href="tel:+330499999999">"tel:+330499999999"</a><br/>
                <a class="contact" href="mailto:hello@sardinescamp.com">hello@sardinescamp.com</a>
            </address>

            <br/>
                
            <h3> Informations client </h3><br/>
            <dl>
                <dt>Id utilisateur</dt>
                <dd><?php echo  $resultat_info_customer[0]['id_utilisateur'] ?></dd>
                
                <dt>Nom et Prénom</dt>
                <dd><?php echo  $resultat_info_customer[0]['prenom'].' '.$resultat_info_customer[0]['nom'] ?></dd>
                
                <dt>Numéro de téléphone</dt>
                <dd><?php echo  $resultat_info_customer[0]['num_tel'] ?></dd>
                
                <dt>Mail</dt>
                <dd><?php echo  $resultat_info_customer[0]['email'] ?></dd>
            </dl>
            
            <br/>
            
            <h3> Récapitulatif du séjour</h3><br/>
            <dl>
                <dt>Date de séjour</dt>
                <dd>Arrivée le : <?php echo $resultat_info_reservation[0]['date_debut'] ?> _ Départ le : <?php echo $resultat_info_reservation[0]['date_fin'] ?></dd><br/>
                
                <dt>Site réservé et tarif journalier</dt>
                <dd>
                    <?php foreach($resultat_info_lieu as $info_lieu){echo $info_lieu['nom_lieu'].' _ '. $info_lieu['prix_journalier'].'€/j (1 emplacement)';}?>
                </dd><br/>
                
                <dt>Type(s) d'emplacement réservé(s)</dt>
                <dd>
                    <?php
                    foreach($resultat_info_type as $info_type)
                    {
                        echo $info_type['nom_type_emplacement'].' ('.
                        $info_type['nb_emplacements_reserves'].' emplacement(s) réservé(s) ) <br/>';
                    }
                    ?>
                </dd><br/>
                
                <dt>Option(s) sélectionnée(s) et tarif</dt>
                <dd>
                    <?php 
                         foreach($resultat_info_option as $info_option)
                        {
                            echo $info_option['nom_option'] .' '.
                            $info_option['prix_option'].' €/j <br/>';
                        }
                    ?>
                </dd>
            </dl>
            
            <br/>
            
            <h3>TOTAL</h3><br/>
            <dl>
                <dt>Total emplacement(s) réservé(s)</dt>
                <dd><?php echo $resultat_info_prix[0]['nb_emplacement'] ?></dd>
                <dt>Total tarif journalier emplacement(s)</dt>
                <dd><?php echo $resultat_info_prix[0]['prix_journalier'].' €' ?></dd>
                <dt>Total tarif journalier option(s) </dt>
                <dd><?php echo $resultat_info_prix[0]['prix_options'] .' €'?></dd>
                <dt>Total journée(s) réservée(s)</dt>
                <dd><?php echo $resultat_info_prix[0]['nb_jours'] ?></dd>
                <dt>Total coût séjour</dt>
                <dd id="total"><?php echo $resultat_info_prix[0]['prix_total'].' €' ?></dd>
            </dl>
            
            
            <button onclick="myFunction()"  class="button_print"><i class="fas fa-print"></i></button>
          
    
            <script>
            function myFunction() {
                window.print();
            }
            </script>
           
                  
            <br/>
             
                
                
                
                
            <?php
    
            
            
            
            
            function update_reservation($id_reservation,$arrival, $departure,$nb_jours, $total_prix_journalier, $total_prix_option)
                
            {
                
                try
                {
                     //CONNEXION BDD
                    $connexion=new PDO("mysql:host=localhost;dbname=camping",'root','');
                    // DEFINITION MODE D'ERREUR PDO SUR EXCEPTION
                    $connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                    
                    
                    
                    $prix_total = $nb_jours * $total_prix_journalier + $total_prix_option ;


                    $update_date = "UPDATE reservations SET date_debut=:date_debut, date_fin=:date_fin WHERE id_reservation = $id_reservation ";
                    $update_date_exec = $connexion->prepare($update_date);
                    $update_date_exec -> bindparam(':date_debut',$arrival, PDO::PARAM_STR);
                    $update_date_exec -> bindparam(':date_fin',$departure, PDO::PARAM_STR);
                    $update_date_exec->execute();

                    $update_prix = "UPDATE prix_detail SET nb_jours=:nb_jours, prix_total=:prix_total WHERE id_reservation = $id_reservation"; 
                    $update_prix_exec = $connexion->prepare($update_prix);
                    $update_prix_exec -> bindparam(':nb_jours',$nb_jours, PDO::PARAM_INT);
                    $update_prix_exec -> bindparam(':prix_total',$prix_total, PDO::PARAM_STR);
                    $update_prix_exec->execute();
                }
          
                catch (PDOException $e) 
                {
                echo "Erreur : " . $e->getMessage();
                }

            }
    
    
    
            if($_SESSION['user']['is_admin'])
            {
               ?>
                
                 <form method="post" id="modif_form" action="facturation.php?id_reservation=<?php echo $resultat_info_reservation[0]['id_reservation']?>#modif_form" class="modif_date_reservation">
                   
                    <h3>Modification date de séjour</h3><br/>
                    <div class="input_modif_date_reservation">
                        <div>
                            <label for="arrival">Date d'arrivée</label><br/>
                            <input type="date" name="arrival">
                        </div>
                        <div>
                            <label for="departure">Date de départ</label><br/>
                            <input type="date" name="departure" id="departure">
                        </div>
                    </div>
                    <div class="button_modif_resa">
                        <br/><input type="submit" name="modif_resa">
                    </div>
                    
                </form>
                
                
                
                <?php
                
                
                
                if(isset($_POST['modif_resa']))
                {
                    $id_reservation = $resultat_info_reservation[0]['id_reservation'];
                    $place_original=$info_lieu['nom_lieu'];
                    $total_prix_journalier = $resultat_info_prix[0]['prix_journalier'];
                    
                    
                    $arrival=$_POST['arrival'];
                    $departure=$_POST['departure'];
                    $debut=strtotime($arrival);
                    $fin=strtotime($departure);
                    $nb_jours = ceil(abs($fin - $debut) / 86400 + 1);
                    $total_prix_option = $resultat_info_prix[0]['prix_options'] * $nb_jours;
                    
                    if(!empty($arrival) AND !empty($departure))
                    {

                        // si la date de debut d'un séjour déjà enregistré est compris entre le debut et la fin de ma sélection
                        $recup_dates_resa = $connexion->prepare(" SELECT * FROM reservations WHERE date_debut>='$arrival' AND date_debut<='$departure' ");
                        $recup_dates_resa->execute();
                        $result_recup_dates1 = $recup_dates_resa->rowCount();
                        $result_recup_dates2 = $recup_dates_resa->FetchAll();
                        
                        //var_dump($result_recup_dates1,$result_recup_dates2);
                        
                        if($result_recup_dates1 >=1)
                        {
                            //recuperation id_reservation des reservations qui match
                            foreach($result_recup_dates2 as $date_match)
                             {
                            $id_reservation_match_date = $date_match['id_reservation'];
                            //var_dump($date_match['id_reservation']);
                            //echo $id_reservation_match_date ."<br/>";

                            //est ce qu'il y a correspondance des lieux 
                            $recup_lieux_resa = $connexion->prepare("SELECT * FROM detail_lieux WHERE id_reservation = $id_reservation_match_date AND nom_lieu = '$place_original' ");
                            $recup_lieux_resa->execute();
                            $result_recup_lieux1 = $recup_lieux_resa->rowCount();
                            $result_recup_lieux2 = $recup_lieux_resa->FetchAll();

                            }
                            
                            //var_dump($result_recup_lieux1,$result_recup_lieux2);
                            
                            if($result_recup_lieux1 >=1)
                            {
                            
                                //recuperation id_reservation des reservations qui match
                                 foreach($result_recup_lieux2 as $place_match)
                                 {
                                     //echo $place_match['nom_lieu']."<br/>" ;

                                     $id_reservation_match_nb_place = $place_match['id_reservation'];


                                     //combien d'emplacement(s) sont réservés sur le lieu par l'autre ou les autres réservation(s)
                                     $recup_nb_place = $connexion->prepare("SELECT nb_emplacements_reserves FROM detail_types_emplacement WHERE id_reservation = $id_reservation_match_nb_place ");
                                     $recup_nb_place->execute();
                                     $result_recup_nb_place = $recup_nb_place->FetchAll();
                                 }
                                
                                //var_dump($result_recup_nb_place);

                                //recuperation nb de place 
                                foreach($result_recup_nb_place as $nb_place_match)
                                {
                                    //echo $nb_place_match['nb_emplacements_reserves'].'<br/>' ;
                                    $nb_booked = $nb_place_match['nb_emplacements_reserves'] ;
                                }

                                // recuperation capacité totale du lieu
                                $place_check = $place_match['nom_lieu'];    
                                
                                //var_dump($place_check);
                                
                                $recup_nb_place_lieu = $connexion-> prepare("SELECT emplacements_disponibles FROM lieux WHERE nom_lieu = '$place_check'");
                                $recup_nb_place_lieu -> execute();
                                $result_recup_nb_place_lieu = $recup_nb_place_lieu->FetchAll();

                                //var_dump($result_recup_nb_place_lieu);

                                foreach($result_recup_nb_place_lieu as $calc_place)
                                {
                                    //echo $calc_place['emplacements_disponibles'];
                                    $nb_total=$calc_place['emplacements_disponibles'];
                                }

                                $nb_place_reservation = $resultat_info_prix[0]['nb_emplacement'];
                                
                                //var_dump($nb_place_reservation);
                            
                                if($nb_place_reservation <= $nb_total-$nb_booked)
                                {
                                    //echo " <br/>  Update possible car il reste de la place";
                                    update_reservation($id_reservation,$arrival, $departure,$nb_jours, $total_prix_journalier, $total_prix_option);
                                    header('location:facturation.php?id_reservation='.$id_reservation.'');
                                }
                                else
                                {
                                  echo "<br/> Ces dates ne sont pas disponibles";    
                                }
                                
                            }
                            else 
                            {
                                update_reservation($id_reservation,$arrival, $departure,$nb_jours, $total_prix_journalier, $total_prix_option);
                                header('location:facturation.php?id_reservation='.$id_reservation.'');
                                //echo " <br/>  Update possible car le lieu est différent";
                            }
                           
                        }
                        else
                            
                        {
                            update_reservation($id_reservation,$arrival, $departure,$nb_jours, $total_prix_journalier, $total_prix_option);
                            header('location:facturation.php?id_reservation='.$id_reservation.'');
                           //echo ' <br/>  Update possible car aucune réservation à cette date'; 
                        }
                    }
                    else
                    {
                        echo'<br/> Veuillez chosir une date de début et une date de fin';
                    }

                }
                
                
            }
                
                    
               ?>

            
             </div>
        </section>
        
        
    </main>
    <footer>
        <?php include("includes/footer.php") ?>
    </footer>
</body>