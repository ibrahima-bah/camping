<?php 

        //TENTATIVE CONNEXION BDD
        try
        {
            //CONNEXION BDD
            $connexion=new PDO("mysql:host=localhost;dbname=camping",'root','root');
            // DEFINITION MODE D'ERREUR PDO SUR EXCEPTION
            $connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
            //SELECTION DU LIEU ET DU TARIF PAR LIEU
            $data_place_price = $connexion->prepare("SELECT * FROM lieux ");
            //EXECUTION REQUETE
            $data_place_price->execute();
            //RECUPERATION DONNEES TARIFS
            $data_place_price_result = $data_place_price->fetchAll(PDO::FETCH_ASSOC);
            
            //SI ON APPUIS SUR DELETE PLACE
            if (isset($_POST['delete_place']))
            {
                //DEFINITION VARIABLE ID_HIDDEN
                $place_id = $_POST['place_id_hidden'];
                //SUPPRESSION DES DONNEES UTILISATEUR EN BDD
                $place_delete = $connexion->prepare("DELETE FROM lieux WHERE id_lieu = $place_id");
                //EXECUTION REQUETE
                $place_delete->execute();

                //RAFRAICHISSEMENT PAGE
                header("location:admin.php");
            }
            
            
            
            
        }

        catch (PDOException $e) 
        {
        echo "Erreur : " . $e->getMessage();
        }
?>

<section class="admin_table admin_gestion">
    <h2>Gestions des sites</h2><br />
    <div>
        <table>
            <thead>
                <tr>
                    <th>Lieux</th>
                    <th>Places(s)</th>
                    <th>Tarif/j</th>
                    <th>Editer</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_place_price_result as $place) { ?>
                <tr>
                    <td><?php echo html_entity_decode($place['nom_lieu'])?></td>
                    <td><?php echo $place['emplacements_disponibles']?></td>
                    <td><?php echo $place['prix_journalier'] . '€'?></td>
                    <td><a class="user_modify_button" href="admin.php?modifier_lieu=<?php echo $place['nom_lieu'] ?>#modif_sites">EDITER</a></td>
                    <td>
                        <form method="post" action="" class="delete_button">
                            <button type="submit" name="delete_place"><i class="fas fa-times"></i></button>
                            <input type="hidden" name="place_id_hidden" value="<?php echo $place['id_lieu'] ?>">
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div>
        <?php

                    if (isset($_GET['modifier_lieu'])) 
                    {
                        //var_dump($_POST);

                        //DEFINITION VARIABLE NAME_HIDDEN
                        $place_name = $_GET['modifier_lieu'];

                        //SI ON APPUIS SUR MODIFIER LIEUX
                        if (isset($_POST['update_place_submit'])) 
                        {
                            //DEFINITION DES VARIABLES STOCKANT LES LIEUX, NBR EMPLACEMENT PAR LIEU ET TARIFS
                            $update_place = $_POST['update_place_name'];
                            $update_nb_place = $_POST['update_nb_place'];
                            $update_price_place = $_POST['update_price_place'];

                            //SI LE NOM DU LIEU EST RENSEIGNE
                            if (!empty($update_nb_place)) 
                            {
                                //MISE A JOUR NB EMPLACEMENT
                                $update_place_nb = "UPDATE lieux SET emplacements_disponibles=:nb_place WHERE nom_lieu = '$place_name'";
                                //PREPARATION REQUETE
                                $update_place_nb1 = $connexion->prepare($update_place_nb);
                                $update_place_nb1->bindParam(':nb_place', $update_nb_place, PDO::PARAM_INT);
                                //EXECUTION REQUETE

                                $update_place_nb1->execute();
                                var_dump($update_place_nb1->execute());
                            }

                            if (!empty($update_price_place)) 
                            {
                                //MISE A JOUR NB EMPLACEMENT
                                $update_place_price = "UPDATE lieux SET prix_journalier=:price_place WHERE nom_lieu = '$place_name'";
                                //PREPARATION REQUETE
                                $update_place_price1 = $connexion->prepare($update_place_price);
                                $update_place_price1->bindParam(':price_place', $update_price_place, PDO::PARAM_INT);
                                //EXECUTION REQUETE
                                $update_place_price1->execute();
                            }

                            if (!empty($update_place)) {
                                //MISE A JOUR NOM LIEU
                                $update_place_name = "UPDATE lieux SET nom_lieu=:nom_lieu WHERE nom_lieu = '$place_name'";
                                $update_place_name_bis = "UPDATE detail_lieux SET nom_lieu=:nom_lieu WHERE nom_lieu='$place_name' ";
                                //PREPARATION REQUETE
                                $update_place_name1 = $connexion->prepare($update_place_name);
                                $update_place_name1_bis = $connexion->prepare($update_place_name_bis);
                                $update_place_name1->bindParam(':nom_lieu', $update_place, PDO::PARAM_STR);
                                $update_place_name1_bis->bindParam(':nom_lieu', $update_place, PDO::PARAM_STR);
                                //EXECUTION REQUETE
                                $update_place_name1->execute();
                                $update_place_name1_bis->execute();
                            }

                            header("location:admin.php");
                        }


                        ?>
        <form class="form_admin" action='' method='post'>
            <h3 id="modif_sites">Modifier un lieu</h3><br />
            <label for='update_place_name'>Modification nom lieu</label>
            <input type='text' name='update_place_name'>
            <label for='update_nb_place'>Modification du nbr d'emplacement</label>
            <input type='number' name='update_nb_place'>
            <label for='update_price_place'>Modification du tarif</label>
            <input type='number' step='0.01' name='update_price_place'>
            <input type='submit' name='update_place_submit' value='EDITER'>
        </form>

        <?php } ?>

        <form class="form_admin" method="post" action="admin.php#gestion_sites">
            <h3 id="gestion_sites">Ajouter un nouveau lieu</h3><br />
            <label for="place">Lieux</label>
            <input type="text" name="place">
            <label for="place">Nbr d'emplacement(s) par lieu</label>
            <input type="number" name="number_place">
            <label for="place">Tarif journalier</label>
            <input type="number" step="0.01" name="price_place">
            <?php
        //SI ON APPUIS SUR AJOUTER UN LIEU
                if(isset ($_POST['add_place']))
                {
                    //DEFINITION DES VARIABLES STOCKANT LES LIEUX, NBR EMPLACEMENT PAR LIEU ET TARIFS
                    $place=$_POST['place'];
                    $nbr_place=$_POST['number_place'];
                    $tarif=$_POST['price_place'];

                        //SI LES CHAMPS PRECEDENTS SONT RENSEIGNES
                        if($place AND $nbr_place AND $tarif)
                        {
                            // VERIFICATION CORRESPONDANCE BDD
                            $check_place_match = $connexion->prepare ("SELECT * FROM lieux WHERE nom_lieu = '$place' ");
                            // EXECUTION REQUETE
                            $check_place_match->execute();
                            //RECUPERATION DONNEES
                            $check_place_match_result = $check_place_match->rowCount();

                                //SI IL EXISTE DEJA DANS LA BDD
                                if($check_place_match_result>=1)
                                {
                                    echo'Ce lieu existe déjà';
                                }
                                else
                                {
                                //INSERTION NOUVEAU LIEU
                                $insert_place = "INSERT INTO lieux (nom_lieu,emplacements_disponibles,prix_journalier) VALUES (:place,:nbr_place, :tarif)";
                                //PREPARATION REQUETE
                                $insert_place1 = $connexion->prepare($insert_place);
                                $insert_place1->bindParam(':place',$place, PDO::PARAM_STR);
                                $insert_place1->bindParam(':nbr_place',$nbr_place, PDO::PARAM_INT);
                                $insert_place1->bindParam(':tarif',$tarif, PDO::PARAM_INT);
                                //EXECUTION REQUETE
                                $insert_place1->execute();
                                header("location:admin.php");
                                }
                        }
                        else
                        {
                        $errors[] ='Veuillez remplir tous les champs <br/><br/>';
                        $message = new messages($errors);
                        echo $message->renderMessage();
                        }
                }

        ?>

            <input type="submit" name="add_place" value="VALIDER">
        </form>
    </div>


</section>