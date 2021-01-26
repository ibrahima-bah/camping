<?php 

        //TENTATIVE CONNEXION BDD
        try
        {
            //CONNEXION BDD
            $connexion=new PDO("mysql:host=localhost;dbname=camping",'root','root');
            // DEFINITION MODE D'ERREUR PDO SUR EXCEPTION
            $connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
            //SELECTION DE TOUTES LES DONNEES UTILISATEURS
            $data_users = $connexion->prepare("SELECT * FROM utilisateurs");
            //EXECUTION REQUETE
            $data_users->execute();
            //RECUPERATION RESULTAT
            $resultat_data_users = $data_users->fetchAll(PDO::FETCH_ASSOC);
            
            //AJOUT NOUVEL UTILISATEUR
                if (isset($_POST['submit']))
                {
                    $user2 = new users ($db);
                    $user2->register(
                        $_POST['firstname'],
                        $_POST['lastname'],
                        $_POST['email'],
                        $_POST['password'],
                        $_POST['conf_password'],
                        $_POST['num_tel'],
                        $_POST['gender']
                    );
                }
            
            //SI ON APPUIS SUR DELETE UTILISATEUR
                    if (isset($_POST['delete_user'])) 
                    {
                        //DEFINITION VARIABLE ID_HIDDEN
                        $user_id = $_POST['id_hidden'];

                        //SUPPRESSION DES DONNEES UTILISATEUR EN BDD
                        $user_delete = $connexion->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = $user_id ");
                        //EXECUTION REQUETE
                        $user_delete->execute();

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
        <h2>Tableau utilisateurs</h2><br/>
        <table>
            <thead>
                <tr>
                    <th class="display_none">Avatar</th>
                    <th>Id</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th class="display_none">Sexe</th>
                    <th class="display_none">Email</th>
                    <th>Téléphone</th>
                    <th class="display_none">Date d'enregistrement</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($resultat_data_users as $info_users ){ ?>
                <tr>
                    <td class="display_none avatar">
                        <img src="<?php if($info_users['avatar'] == NULL)
                                                        {
                                                            echo 'images/no-image.png';
                                                        }
                                                        else
                                                        {
                                                            echo $info_users['avatar'] ;
                                                        }?>" alt="avatar" width='30' height='30'>
                    </td>
                    <td><?php echo $info_users ['id_utilisateur']?></td>
                    <td><?php echo html_entity_decode($info_users['nom']) ?></td>
                    <td><?php echo html_entity_decode($info_users['prenom']) ?></td>

                    <td class="display_none">
                        <?php
                                            if($info_users ['gender'] == "Femme"){
                                                echo '<i class="fas fa-venus"></i>';
                                            }
                                            elseif($info_users ['gender'] == "Homme")
                                            {
                                                echo'<i class="fas fa-mars"></i>';
                                            }
                                            else
                                            {
                                                echo'<i class="fas fa-genderless"></i></i>';
                                            }
                                        ?>
                    </td>
                    <td class="display_none"><?php echo $info_users ['email'] ?></td>
                    <td><?php echo $info_users ['num_tel'] ?></td>
                    <td class="display_none"><?php echo $info_users ['register_date'] ?></td>

                    <td>
                        <a class="user_modify_button" href="cpte_users.php?id=<?php echo $info_users['id_utilisateur']?>">MODIFIER</a>
                    </td>
                    <td class="delete_button">
                        <form method="post" action="">
                            <button type="submit" name="delete_user"><i class="fas fa-trash-alt"></i></button>
                            <input type="hidden" name="id_hidden" value="<?php echo $info_users ['id_utilisateur']  ?>">
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <form method="post" action="" class="form_user">
            <h2>Ajouter un nouvel utilisateur</h2><br/>
            <div class="form_admin_user">
                <div class="section_add_list" id="gender_part">
                    <div class="gender_column">
                        <label>Genre</label>
                        <div class="gender_line">
                            <input type="radio" name="gender" id="male" value="Homme" checked="checked">
                            <label for="male">Homme</label>
                        </div>
                        <div class="gender_line">
                            <input type="radio" name="gender" id="female" value="Femme">
                            <label for="female">Femme</label>
                        </div>
                        <div class="gender_line">
                            <input type="radio" name="gender" id="no_gender" value="Non genré">
                            <label for="no_gender">Non genré</label>
                        </div>
                    </div>
                </div>
                <div class="section_add_list">
                    <label for="lastname">Nom</label>
                    <input type="text" name="lastname" placeholder="Nom" autocomplete="on">
                    <label for="firstname">Prénom</label>
                    <input type="text" name="firstname" placeholder="Prénom" autocomplete="on">
                    <label for="num_tel">Numéro de téléphone</label>
                    <input type="text" name="num_tel" placeholder="0123456789" autocomplete="on">

                </div>
                <div class="section_add_list">

                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="email@email.com" autocomplete="on">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" placeholder="Mot de passe">
                    <label for="conf_password">Confirmation mot de passe</label>
                    <input type="password" name="conf_password" placeholder="Confirmer mot de passe">
                </div>
            </div>
            <div>
                <button type="submit" name="submit">Enregistrer</button>
            </div>
        </form>
    </div>