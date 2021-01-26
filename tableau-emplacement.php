<?php 
    //TENTATIVE CONNEXION BDD
        try
        {
            //CONNEXION BDD
            $connexion=new PDO("mysql:host=localhost;dbname=camping",'root','');
            // DEFINITION MODE D'ERREUR PDO SUR EXCEPTION
            $connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
            //SELECTION DU TYPE D'EMPLACEMENT ET DU NB
            $data_place_type = $connexion->prepare("SELECT * FROM types_emplacement ");
            //EXECUTION REQUETE
            $data_place_type->execute();
            //RECUPERATION DONNEES TARIFS
            $data_place_type_result = $data_place_type->fetchAll(PDO::FETCH_ASSOC);

            //SI ON APPUIS SUR DELETE TYPE
            if (isset($_POST['delete_type'])) 
            {
                //DEFINITION VARIABLE ID_HIDDEN
                $type_id = $_POST['type_id_hidden'];
                //SUPPRESSION DES DONNEES UTILISATEUR EN BDD
                $type_delete = $connexion->prepare("DELETE FROM types_emplacement WHERE id_type_emplacement = $type_id ");
                //EXECUTION REQUETE
                $type_delete->execute();

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
    <h2>Gestion des types d'emplacements</h2><br/>
    <table>
        <thead>
            <tr>
                <th>Type d'emplacement</th>
                <th>Taille</th>
                <th>Editer</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php
                    foreach ($data_place_type_result as $type) { ?>
            <tr>
                <td><?php echo html_entity_decode($type['nom_type_emplacement']) ?></td>
                <td><?php echo $type['nb_emplacements'] ?></td>
                <td>
                    <a class="user_modify_button" href="admin.php?modifier_type=<?php echo $type['id_type_emplacement'] ?>#modif_emplacement">EDITER</a>
                </td>
                <td>
                    <form method="post" action="" class="delete_button">
                        <button type="submit" name="delete_type"><i class="fas fa-times"></i></button>
                        <input type="hidden" name="type_id_hidden" value="<?php echo $type['id_type_emplacement'] ?>">
                    </form>
                </td>
            </tr>
            <?php
                    } ?>
        </tbody>
    </table>

    <?php
                if (isset($_GET['modifier_type'])) {
                    //DEFINITION VARIABLE TYPE_HIDDEN
                    $type_name = $_GET['modifier_type'];

                    //SI ON APPUIS SUR MODIFIER TYPE D'EMPLACEMENT
                    if (isset($_POST['update_type_submit'])) {
                        //DEFINITION DES VARIABLES STOCKANT LES TYPES D'EMPLACEMENT ET LEUR TAILLE
                        $update_type_name = $_POST['update_type_name'];
                        $update_size_type = $_POST['update_size'];

                        //DEFINITION VARIABLE ID_HIDDEN
                        $type_id2 = $_POST['type_id_hidden2'];

                        //SI LE TYPE D'EMPLACEMENT EST RENSEIGNE
                        if (!empty($update_type_name)) {
                            //MISE A JOUR TYPE D'EMPLACEMENT
                            $update_type = "UPDATE types_emplacement SET nom_type_emplacement=:nom_type WHERE id_type_emplacement = $type_id2";
                            //PREPARATION REQUETE
                            $update_type1 = $connexion->prepare($update_type);
                            $update_type1->bindParam(':nom_type', $update_type_name, PDO::PARAM_STR);
                            //EXECUTION REQUETE
                            $update_type1->execute();

                            header("location:admin.php");
                        }

                        //SI LA TAILLE DU TYPE D'EMPLACEMENT EST RENSEIGNE
                        if (!empty($update_size_type)) {
                            //MISE A JOUR TAILLE EMPLACEMENT
                            $update_size = "UPDATE types_emplacement SET nb_emplacements=:nb_size WHERE id_type_emplacement = $type_id2";
                            //PREPARATION REQUETE
                            $update_size1 = $connexion->prepare($update_size);
                            $update_size1->bindParam(':nb_size', $update_size_type, PDO::PARAM_INT);
                            //EXECUTION REQUETE
                            $update_size1->execute();

                            header("location:admin.php");
                        }
                    }

                    ?>

    <form class="form_admin" method="post" action="">
        <h3 id="modif_emplacement">Modifier un type d'emplacement</h3><br/>
        <label for="update_type_name">Modification type d'emplacement</label>
        <input type="text" name="update_type_name">
        <label for="update_size">Modification taille emplacement</label>
        <input type="number" name="update_size">
        <input type="hidden" name="type_id_hidden2" value="<?php echo $type['id_type_emplacement'] ?>">
        <input type="submit" name="update_type_submit" value="MODIFIER">
    </form>

    <?php } ?>


    <form class="form_admin" method="post" action="admin.php#gestion_emplacement">
        <h3 id="gestion_emplacement">Ajouter un nouveau type d'emplacement</h3><br/>
        <label for="type">Type d'emplacement</label>
        <input type="text" name="type">
        <label for="number_place_type">Taille emplacement</label>
        <input type="number" name="number_place_type">
        
        <?php 
        
        //SI ON APPUIS SUR AJOUTER UN TYPE D'EMPLACEMENT 
                if(isset ($_POST['add_type']))
                {
                    //DEFINITION DES VARIABLES STOCKANT LES TYPES D'EMPLACEMENTS ET LEUR TAILLE
                    $type_place=$_POST['type'];
                    $nbr_place_type=$_POST['number_place_type'];
                    //SI LES CHAMPS PRECEDENTS SONT RENSEIGNES
                    if( $type_place AND $nbr_place_type)
                    {
                        // VERIFICATION CORRESPONDANCE BDD 
                        $check_type_match = $connexion->prepare ("SELECT * FROM types_emplacement WHERE nom_type_emplacement = '$type_place' ");
                        // EXECUTION REQUETE
                        $check_type_match->execute();
                        //RECUPERATION DONNEES
                        $check_type_match_result = $check_type_match->rowCount();
                        
                        //SI IL EXISTE DEJA DANS LA BDD
                        if($check_type_match_result>=1)
                        {
                           echo'Ce type d\'emplacement existe déjà'; 
                        }
                        else
                        {
                            //INSERTION NOUVEAU TYPE
                            $insert_type = "INSERT INTO types_emplacement (nom_type_emplacement,nb_emplacements) VALUES (:type,:nbr_place_type)";
                            //PREPARATION REQUETE
                            $insert_type1 = $connexion->prepare($insert_type);
                            $insert_type1->bindParam(':type',$type_place, PDO::PARAM_STR);
                            $insert_type1->bindParam(':nbr_place_type',$nbr_place_type, PDO::PARAM_INT);
                            //EXECUTION REQUETE
                            $insert_type1->execute(); 
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
        
        <input type="submit" name="add_type" value="VALIDER">
    </form>
</section>