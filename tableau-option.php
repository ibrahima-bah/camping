<?php

//TENTATIVE CONNEXION BDD
        try
        {
            //CONNEXION BDD
            $connexion=new PDO("mysql:host=localhost;dbname=camping",'root','');
            // DEFINITION MODE D'ERREUR PDO SUR EXCEPTION
            $connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
            //SELECTION DES OPTIONS ET DE LEUR PRIX
            $data_option_price = $connexion->prepare("SELECT * FROM options ");
            //EXECUTION REQUETE
            $data_option_price->execute();
            //RECUPERATION DONNEES 
            $data_option_price_result = $data_option_price->fetchAll(PDO::FETCH_ASSOC);

            //SI ON APPUIS SUR DELETE OPTION
            if(isset($_POST['delete_option'])) 
            {
                //DEFINITION VARIABLE ID_HIDDEN
                $option_id = $_POST['option_id_hidden'];
                //SUPPRESSION DES DONNEES UTILISATEUR EN BDD
                $option_delete = $connexion->prepare("DELETE FROM options WHERE id_option = $option_id");
                //EXECUTION REQUETE
                $option_delete->execute();
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
    <h2> Gestion des options</h2><br/>
    <table>
        <thead>
            <tr>
                <th>Options</th>
                <th>Tarifs</th>
                <th>Editer</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php
                    foreach ($data_option_price_result as $option) { ?>
            <tr>
                <td><?php echo ($option['nom_option']) ?></td>
                <td><?php echo $option['prix_option'] . '€' ?></td>
                <td>
                    <a class="user_modify_button" href="admin.php?modifier_option=<?php echo $option['id_option'] ?>#modif_options">EDITER</a>
                </td>
                <td>
                    <form method="post" action="" class="delete_button">
                        <button type="submit" name="delete_option"><i class="fas fa-times"></i></button>
                        <input type="hidden" name="option_id_hidden" value="<?php
                                    echo $option['id_option'] ?>">
                    </form>
                </td>
            </tr>
            <?php
                    } ?>
        </tbody>
    </table>
    <?php

                if (isset($_GET['modifier_option'])) {
                    //DEFINITION VARIABLE ID_HIDDEN
                    $option_id2 = $_GET['modifier_option'];

                    //SI ON APPUIS SUR MODIFIER OPTION
                    if (isset($_POST['update_option_submit'])) {
                        //DEFINITION DES VARIABLES STOCKANT LES TYPES D'EMPLACEMENT ET LEUR TAILLE
                        $update_option_name = $_POST['update_option_name'];
                        $update_option_price = $_POST['update_price_option'];


                        //var_dump($POST);
                        //SI L'OPTION EST RENSEIGNE
                        if (!empty($update_option_name)) {
                            //MISE A JOUR TYPE D'EMPLACEMENT
                            $update_option = "UPDATE options SET nom_option=:nom_option WHERE id_option = $option_id2";
                            //PREPARATION REQUETE
                            $update_option1 = $connexion->prepare($update_option);
                            $update_option1->bindParam(':nom_option', $update_option_name, PDO::PARAM_STR);
                            //EXECUTION REQUETE
                            $update_option1->execute();

                            header("location:admin.php");
                        }

                        //SI LE PRIX DE L'OPTION EST RENSEIGNE
                        if (!empty($update_option_price)) {
                            //MISE A JOUR TAILLE EMPLACEMENT
                            $update_price_option = "UPDATE options SET prix_option=:prix_option WHERE id_option = $option_id2";
                            //PREPARATION REQUETE
                            $update_price_option1 = $connexion->prepare($update_price_option);
                            $update_price_option1->bindParam(':prix_option', $update_option_price, PDO::PARAM_INT);
                            //EXECUTION REQUETE
                            $update_price_option1->execute();

                            header("location:admin.php");
                        }
                    }
                    ?>

    <form class="form_admin" method="post" action="">
        <h3 id="modif_options">Modifier une option</h3><br/>
        <label for="update_option_name">Modification option</label>
        <input type="text" name="update_option_name">
        <label for="update_price_option">Modification tarifs</label>
        <input type="number" step="0.01" name="update_price_option">
        <input type="submit" name="update_option_submit" value="MODIFIER">
    </form>

    <?php } ?>

    <form class="form_admin" method="post" action="admin.php#gestion_options">
        <h3 id="gestion_options">Ajouter une nouvelle option</h3><br/>
        <label for="option">Options</label>
        <input type="text" name="option">
        <label for="place">Tarifs</label>
        <input type="number" step="0.01" name="price_option">
        
        <?php 
        
        //SI ON APPUIS SUR AJOUTER UNE OPTION
            if(isset ($_POST['add_option']))
            {
                //DEFINITION DES VARIABLES STOCKANT OPTIONS ET TARIFS
                $option =$_POST['option'];
                $price_option = $_POST['price_option'];
                
                //SI LES CHAMPS PRECEDENTS SONT RENSEIGNES
                if($option AND $price_option)
                {
                    // VERIFICATION CORRESPONDANCE BDD
                    $check_option_match = $connexion->prepare ("SELECT * FROM options WHERE nom_option = '$option' ");
                    // EXECUTION REQUETE
                    $check_option_match->execute();
                    //RECUPERATION DONNEES
                    $check_option_match_result = $check_option_match->rowCount();

                        //SI IL EXISTE DEJA DANS LA BDD
                        if($check_option_match_result>=1)
                        {
                            echo'Cette option existe déjà';
                        }
                        else
                        {
                        //INSERTION NOUVELLE OPTION
                        $insert_option = "INSERT INTO options (nom_option,prix_option) VALUES (:option,:prix_option)";
                        //PREPARATION REQUETE
                        $insert_option1 = $connexion->prepare($insert_option);
                        $insert_option1->bindParam(':option',$option, PDO::PARAM_STR);
                        $insert_option1->bindParam(':prix_option',$price_option, PDO::PARAM_INT);
                        //EXECUTION REQUETE
                        $insert_option1->execute();
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
        
        
        <input type="submit" name="add_option" value="VALIDER">
    </form>
</section>