<?php 

        //TENTATIVE CONNEXION BDD
        try
        {
            //CONNEXION BDD
            $connexion=new PDO("mysql:host=localhost;dbname=camping",'root','');
            // DEFINITION MODE D'ERREUR PDO SUR EXCEPTION
            $connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
            //SELECTION INFO UTILISATEUR ET ID RESERVATION
            $data_user_booking = $connexion->prepare("SELECT 
            utilisateurs.id_utilisateur, 
            utilisateurs.nom, 
            utilisateurs.prenom, 
            reservations.id_reservation, 
            reservations.date_debut,
            reservations.date_fin
            FROM utilisateurs, reservations WHERE 
            utilisateurs.id_utilisateur = reservations.id_utilisateur ORDER by date_debut DESC");
            //EXECUTION REQUETE
            $data_user_booking->execute();
            //RECUPERATION RESULTAT
            $resultat_data_user_booking = $data_user_booking->fetchAll(PDO::FETCH_ASSOC);

            //SI ON APPUIS SUR DELETE RESERVATION
                    if (isset($_POST['delete_booking'])) 
                    {
                        //DEFINITION VARIABLE ID_HIDDEN
                        $booking_id = $_POST['booking_id_hidden'];

                        //SUPPRESSION DANS RESERVATIONS 
                        $booking_delete1 = $connexion->prepare("DELETE FROM reservations WHERE id_reservation = $booking_id
                        
                        
                        ");
                        //EXECUTION REQUETE
                        $booking_delete1->execute();
                        
                        //SUPPRESSION DANS DETAILS LIEUX
                        $booking_delete2 = $connexion->prepare(" DELETE FROM detail_lieux WHERE id_reservation = $booking_id");
                        //EXECUTION REQUETE
                        $booking_delete2->execute();
                        
                        //SUPPRESSION DANS DETAILS TYPES EMPLACEMENT
                        $booking_delete3 = $connexion->prepare(" DELETE FROM  detail_types_emplacement WHERE id_reservation = $booking_id");
                        //EXECUTION REQUETE
                        $booking_delete3->execute();
                        
                        //SUPPRESSION DANS DETAILS OPTIONS
                        $booking_delete4 = $connexion->prepare(" DELETE FROM  detail_options WHERE id_reservation = $booking_id");
                        //EXECUTION REQUETE
                        $booking_delete4->execute();
                        
                        //SUPPRESSION DANS DETAILS OPTIONS
                        $booking_delete5 = $connexion->prepare(" DELETE FROM   prix_detail WHERE id_reservation = $booking_id");
                        //EXECUTION REQUETE
                        $booking_delete5->execute();
                        
                        
                        
                        
                        
                        //RAFRAICHISSEMENT PAGE
                        header("location:admin.php");
                    }

        }

        catch (PDOException $e) 
        {
        echo "Erreur : " . $e->getMessage();
        }

?>

<div class="taille_table_admin">
    <h2>Tableau des réservations </h2><br/>
    <table>
        <thead>
            <?php foreach($resultat_data_user_booking as $booking){ ?>
            <tr>
                <th class="display_none">Id utilisateur</th>
                <th class="display_none">Nom</th>
                <th class="display_none">Prénom</th>
                <th>Id réservation</th>
                <th>Arrivée</th>
                <th>Départ</th>
                <th>Détails réservation</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="display_none"><?php echo $booking['id_utilisateur'] ?></td>
                <td class="display_none"><?php echo $booking['nom'] ?></td>
                <td class="display_none"><?php echo $booking['prenom'] ?></td>
                <td><?php echo 'Réservation n°'.$booking['id_reservation'] ?></td>
                <td><?php echo $booking['date_debut'] ?></td>
                <td><?php echo $booking['date_fin'] ?></td>
                <td><a class="user_modify_button" href="facturation.php?id_reservation=<?php echo $booking['id_reservation']?>">FACTURE</a></td>
                <td>
                    <form method="post" action="" class="delete_button">
                        <button type="submit" name="delete_booking"><i class="fas fa-trash-alt"></i></button>
                        <input type="hidden" name="booking_id_hidden" value="<?php echo $booking['id_reservation'] ?>">
                    </form>
                </td>
            </tr>
            <?php  } ?>
        </tbody>
    </table>
</div>
