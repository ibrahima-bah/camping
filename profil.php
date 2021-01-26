<?php
ob_start();
$page_selected = 'profil';
?>

<!DOCTYPE html>
<html>

<head>
    <title>camping - profil</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes"/>
    <link rel="shortcut icon" type="image/x-icon" href="css/images/logo1.jpg">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style1.css">
</head>

<body>
    <header>
        <?php 
        include("includes/header.php"); 
        $errors = [];
        ?>
    </header>
    <main class="main_profil">
        <?php
        
            //TENTATIVE CONNEXION BDD
            try
                {
                    //CONNEXION BDD
                    $connexion = $db->connectDb();
                    //DEFINITION MODE ERREUR PDO SUR EXCEPTION

                        //DEFINITION DE VARIABLE STOCKANT LA SESSION EN COURS
                        $session=$_SESSION['user']['id_user'];

                        //RECUPERATION DES DONNEES UTILISATEURS
                        $user_session_data = $connexion->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = $session ");
                        //EXECUTION DE LA REQUETE
                        $user_session_data->execute();
                        //RECUPERATION RESULTAT
                        $user_session_data_result = $user_session_data->fetchAll(PDO::FETCH_ASSOC);

                         //var_dump($user_session_data_result);

                        //SI ON APPUIS SUR L'ENVOI DE FICHIER
                        if(isset($_POST['send']))
                        {
                                //DEFINITION DES VARIABLES STOCKANT LA PHOTO ET LE CHEMIN VERS LA PHOTO
                               $file_name=$_FILES["photo"]["name"];
                                $avatar="uploads/$file_name";

                                //SI AUCUN AVATAR EXISTE POUR MA SESSION EN COURS
                                if( $user_session_data_result[0]['avatar'] == "NULL")
                                {
                                    //INSERTION AVATAR
                                    $insert_avatar="INSERT INTO utilisateurs (avatar) VALUES (:avatar) WHERE id_utilisateur = '$session'";
                                    //PREPARATION REQUETE
                                   $insert1= $connexion->prepare($insert_avatar);
                                   $insert1->bindParam(':avatar',$avatar, PDO::PARAM_STR);
                                    //EXECUTION REQUETE
                                   $insert1->execute();

                                }
                            else
                                {
                                    //MISE A JOUR AVATAR
                                    $update_avatar="UPDATE utilisateurs SET avatar = :avatar WHERE id_utilisateur = '$session'";
                                    //PREPARATION REQUETE
                                   $update1= $connexion->prepare($update_avatar);
                                   $update1->bindParam(':avatar',$avatar, PDO::PARAM_STR);
                                    //EXECUTION REQUETE
                                   $update1->execute();
                                }

                                if($_SERVER["REQUEST_METHOD"] == "POST")
                                {
                                    // Vérifie si le fichier a été uploadé sans erreur.
                                    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0)
                                    {
                                        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
                                        $filename = $_FILES["photo"]["name"];
                                        $filetype = $_FILES["photo"]["type"];
                                        $filesize = $_FILES["photo"]["size"];
                                        // Vérifie l'extension du fichier
                                        $ext = pathinfo($filename, PATHINFO_EXTENSION);
                                        if(!array_key_exists($ext, $allowed)) die("Erreur : Veuillez sélectionner un format de fichier valide.");
                                        // Vérifie la taille du fichier - 5Mo maximum
                                        $maxsize = 5 * 1024 * 1024;
                                        if($filesize > $maxsize) die("Error: La taille du fichier est supérieure à la limite autorisée.");
                                        // Vérifie le type MIME du fichier
                                        if(in_array($filetype, $allowed))
                                        {
                                            // Vérifie si le fichier existe avant de le télécharger.
                                            if(file_exists("uploads/".$_FILES["photo"]["name"]))
                                            {
                                                echo $_FILES["photo"]["name"] . " existe déjà.";
                                            }
                                            else
                                            {
                                                move_uploaded_file($_FILES["photo"]["tmp_name"], "uploads/" . $_FILES["photo"]["name"]);
                                                header('location:profil.php');
                                            } 
                                        }
                                        else
                                        {
                                             $errors[] = "Error: Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer.";
                                            $errors[] = "Error: Téléchargement du fichier impossible. Veuillez réessayer.";
                                        }
                                    }
                                    else
                                    {
                                    $errors[] = "Error: " . $_FILES["photo"]["error"];
                                    }
                                }
                        }

                        //SI ON SUPPRIME LA PHOTO
                        if(isset($_POST['delete']))

                        {
                            //SI UN AVATAR EXISTE BIEN EN BDD
                            if($user_session_data_result[0]['avatar'] != NULL)
                            {
                                $avatar_delete = NULL ;
                                //SUPPRESSION AVATAR EN BDD
                                $delete_avatar = "UPDATE utilisateurs SET avatar=:avatar WHERE id_utilisateur = '$session' ";
                                //PREPARATION REQUETE
                                $delete1 = $connexion->prepare($delete_avatar);
                                $delete1->bindParam(':avatar',$avatar_delete, PDO::PARAM_NULL);
                                //EXECUTION REQUETE
                                $delete1->execute();
                                header('location:profil.php');
                            }
                        }






                        //MODIFICATION DES DONNEES DE L'UTILISATEUR SI ON APPUIS SUR VALIDER
                        if(isset($_POST['submit']))
                        {
                            //DEFINITION DES VARIABLES STOCKANT LES DONNEES UTILISATEURS
                            $gender=$_POST['gender'];
                            $lastname=$_POST['lastname'];
                            $firstname=$_POST['firstname'];
                            $mail=$_POST['mail'];
                            $phone=$_POST['phone_number'];
                            $password=$_POST['password'];
                            $check_password=$_POST['check_password'];
                            $hash=password_hash($password,PASSWORD_BCRYPT,array('cost'=>10));
                            
                            
                            
                            
                            //SI LE CHAMPS GENRE EST REMPLI
                            if(!empty($gender))
                            {
                                //MISE A JOUR DES DONNEES
                                $update_gender = "UPDATE utilisateurs SET gender=:gender WHERE id_utilisateur = '$session' ";
                                //PREPARATION REQUETE
                                $update_niv1 = $connexion -> prepare($update_gender);
                                $update_niv1->bindParam(':gender',$gender, PDO::PARAM_STR);
                                //EXECUTION REQUETE
                                $update_niv1->execute();
                            }

                            //SI LE CHAMPS NOM EST REMPLI
                            if(!empty($lastname))
                            {
                                $lastname_required = preg_match("/^(?=.*[A-Za-z]$)([A-Za-z]{2,25}[\s]?[A-Za-z]{1,25})$/", $lastname);
                                if (!$lastname_required) 
                                {
                                    $errors[] = "Le nom doit:<br>- Comporter entre 3 et 50 caractètres.<br>- Commencer et finir par une lettre.<br>- Ne contenir aucun caractère spécial (excepté un espace).";
                                }
                                
                                if (empty($errors)) 
                                {
                                    
                               //MISE A JOUR DES DONNEES
                                $update_lastname = "UPDATE utilisateurs SET nom=:lastname WHERE id_utilisateur = '$session' ";
                                //PREPARATION REQUETE
                                $update_niv2 = $connexion -> prepare($update_lastname);
                                $update_niv2->bindParam(':lastname',$lastname, PDO::PARAM_STR);
                                //EXECUTION REQUETE
                                $update_niv2->execute();
                                } 
                                
                                
                                
                            }


                            //SI LE CHAMPS PRENOM EST REMPLI
                            if(!empty($firstname))
                            {
                                $firstname_required = preg_match("/^(?=.*[A-Za-z]$)[A-Za-z][A-Za-z\-]{2,19}$/", $firstname);
                                
                                if (!$firstname_required) 
                                {
                                    $errors[] = "Le prénom doit :<br>- Comporter entre 3 et 19 caractères.<br>- Commencer et finir par une lettre.<br>- Ne contenir aucun caractère spécial (excepté -).";
                                }
                                
                                if (empty($errors)) 
                                {
                                    
                                //MISE A JOUR DES DONNEES
                                $update_firstname = "UPDATE utilisateurs SET prenom=:firstname WHERE id_utilisateur = '$session' ";
                                //PREPARATION REQUETE
                                $update_niv3 = $connexion -> prepare($update_firstname);
                                $update_niv3->bindParam(':firstname',$firstname, PDO::PARAM_STR);
                                //EXECUTION REQUETE
                                $update_niv3->execute();
                                
                                } 
                            }
                            
                            

                            if(!empty($mail))
                            {
                                
                                $recup_mail_bdd= $connexion->prepare("SELECT * FROM utilisateurs WHERE email='$mail'");
                                $recup_mail_bdd ->execute();
                                $result_mail= $recup_mail_bdd->rowCount();
                                
                                
                                    if($mail != $_SESSION['user']['email'] AND $result_mail>=1)
                                    {
                                      $errors[] = "Cet email existe déjà.";  
                                    }
                                
                                    
                                    $email_required = preg_match("/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/", $mail);

                                    if (!$email_required)
                                    {
                                    $errors[] = "L'email n'est pas conforme.";
                                    }

                                    if (empty($errors))
                                    {

                                    //MISE A JOUR DES DONNEES
                                    $update_mail = "UPDATE utilisateurs SET email=:mail WHERE id_utilisateur = '$session' ";
                                    //PREPARATION REQUETE
                                    $update_niv4 = $connexion -> prepare($update_mail);
                                    $update_niv4->bindParam(':mail',$mail, PDO::PARAM_STR);
                                    //EXECUTION REQUETE
                                    $update_niv4->execute();
                                    }

                                    
                               
                            }
                            

                            if(!empty($phone))
                            {
                                $num_tel_required = preg_match("/^[0-9]{10}$/", $phone);
                                if (!$num_tel_required) 
                                {
                                    $errors[] = "Le numéro de téléphone doit contenir exactement 10 chiffres.";
                                }
                                
                                if (empty($errors)) 
                                {
                                    
                                //MISE A JOUR DES DONNEES
                                $update_phone = "UPDATE utilisateurs SET num_tel=:phone WHERE id_utilisateur = '$session' ";
                                //PREPARATION REQUETE
                                $update_niv5 = $connexion -> prepare($update_phone);
                                $update_niv5->bindParam(':phone',$phone, PDO::PARAM_STR);
                                //EXECUTION REQUETE
                                $update_niv5->execute();
                                
                                } 
                                
                            }


                             //SI LES CHAMPS MOTS DE PASSE ET CONFIRMATION DE MOT DE PASSE SONT  REMPLIS
                            if(!empty($password) OR !empty($check_password))
                            {
                                if($password != $check_password)
                                {
                                $errors[] = "Les champs mots de passe et confirmation de mot de passe doivent être identiques<br />";
                                }

                                $password_required = preg_match(
                                "/^(?=.*?[A-Z]{1,})(?=.*?[a-z]{1,})(?=.*?[0-9]{1,})(?=.*?[\W]{1,}).{8,20}$/",
                                $password
                                );
                                if (!$password_required)
                                {

                                $errors[] = "Le mot de passe doit contenir:<br>- Entre 8 et 20 caractères<br>- Au moins 1 caractère spécial<br>- Au moins 1 majuscule et 1 minuscule<br>- Au moins un chiffre.";
                                }

                                if (empty($errors))
                                {
                                //MISE A JOUR DES DONNEES
                                $update_password = "UPDATE utilisateurs SET password=:hash WHERE id_utilisateur = '$session' ";
                                //PREPARATION REQUETE
                                $update_niv6 = $connexion -> prepare($update_password);
                                $update_niv6->bindParam(':hash',$hash, PDO::PARAM_STR);
                                //EXECUTION REQUETE
                                $update_niv6->execute();

                                }
                            }
                           
                            
                            if (!empty($errors))
                            {
                                $message = new messages($errors);
                                echo $message->renderMessage();
                            }
                            else 
                            {
                                header ('location:profil.php');
                            }
                            
                            
                        }
                

                        if(isset($_POST['delete_account']))
                        {
                            $password=$_POST['password_delete'];
                            $check=$_POST['password_delete_check'];

                            if(!empty($password) AND !empty($check))
                            {
                                if($password == $check);
                                $user->delete($password);
                            }

                        }
                
                        //RECUPERATION DES COMMENTAIRES DE L'UTILISATEUR
                        $commentaire=$connexion->prepare("SELECT * FROM avis WHERE id_utilisateur = $session ORDER BY post_date DESC");
                        //EXECUTION DE LA REQUETE
                        $commentaire->execute();
                        //RECUPERATION RESULTAT
                        $commentaire_result = $commentaire->fetchAll(PDO::FETCH_ASSOC);
                
                        //SUPPRESION D'UN COMMENTAIRE
                        if(isset($_POST['delete_comment']))
                        {
                            $id_avis=$_POST['hidden_id_avis'];
            
                            $delete_com=$connexion->prepare("DELETE FROM avis WHERE id_avis = $id_avis");
                            //EXECUTION DE LA REQUETE
                            $delete_com->execute();
                            header("location:profil.php");
                        }
                
                }
                catch(PDOException $e)
                {
                    echo "Erreur : " . $e->getMessage();
                }


?>

        <section class="profil_section1">  
            <form action="" method="post" enctype="multipart/form-data" class="avatar profil_case_avatar" id="avatar_profil">
                <img src="
                             <?php
                              if($user_session_data_result[0]['avatar'] == NULL){
                                  echo 'css/images/no-image.png';
                              }else{echo $user_session_data_result[0]['avatar'];}
                              ?>" alt="avatar" width="300" height="300"><br/>
                
                <h2><?php echo $user_session_data_result[0]['prenom'].' '.$user_session_data_result[0]['nom'] ?></h2><br />
                <div class="validation_avatar">
                    <label for="file" class="label-file">Choisir une photo</label>
                    <input id="file" type="file" name="photo" class="input-file">
                    <input type="submit" name="send" value="ENVOYER">
                    <button type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
                </div>
                
            </form>
            <div class="profil_case_introduction">
                <h1>Bonjour <?php echo $user_session_data_result[0]['prenom'] ?>, </h1><br/>
                <h2>Un petit mot sur le fonctionnement du site</h2><br/>
                <p>
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci.
                    <br/>
                    <br/>
                    Bon Surf sur le site ! 
                </p>
            </div>
        </section>
        
        <section class="profil_section2">
            <section class="profil_gauche">
                <form action="" method="post" class="profil_case_modification">
                    <h2>Modifier mes données personnelles</h2><br />
                    <?php

                        $gender_check = html_entity_decode($user_session_data_result[0]['gender']);
                        $check = ($gender_check=="Femme")?true:false;
                        $check2 = ($gender_check=="Homme")?true:false;
                        $check3 = ($gender_check=="Non genré")?true:false;

                        ?>

                    <input type="radio" name="gender" value="Femme" <?php if($check==true){echo "checked";}else{echo "";}  ?> />

                    <label for="female">Femme</label>

                    <input type="radio" name="gender" value="Homme" <?php if($check2==true){echo "checked";}else{echo "";} ?> />
                    <label for="male">Homme</label>

                    <input type="radio" name="gender" value="Non genré" <?php if($check3==true){echo "checked";}else{echo "";} ?> />
                    <label for="no_gender">Non genré</label><br />

                    <label for="name">Nom</label><br />
                    <input type="text" name="lastname" value="<?php echo $user_session_data_result[0]['nom'] ?>"><br />
                    <label for="firstname">Prénom</label><br />
                    <input type="text" name="firstname" value="<?php echo $user_session_data_result[0]['prenom'] ?>"><br />


                    <label for="mail">Email</label><br />
                    <input type="mail" name="mail" value="<?php echo $user_session_data_result[0]['email'] ?>"><br />
                    <label for="phone_number">Numéro de téléphone</label><br />
                    <input type="text" name="phone_number" value="<?php echo $user_session_data_result[0]['num_tel'] ?>"><br />

                    <label for="password">Mot de passe</label><br />
                    <input type="password" name="password" placeholder="Entrez votre nouveau mot de passe"><br />
                    <label for="password">Confirmation de mot de passe</label><br />
                    <input type="password" name="check_password" placeholder="Confirmez votre nouveau mot de passe"><br /><br />

                    <input type="submit" name="submit" value="VALIDER"><br />
                </form>
            
                <form action="" method="post" class="profil_case_suppression">
                        <h2>Supprimer définitivement<br /> mon compte</h2><br />
                        <label for="">Entrez votre mot de passe actuel</label><br />
                        <input type="password" name="password_delete" placeholder="Entrez votre mot de passe actuel"><br />
                        <label for="password_delete_check">Confirmez votre mot de passe</label><br />
                        <input type="password" name="password_delete_check" placeholder="Confirmez votre mot de passe actuel"><br /><br />
                        <input type="submit" name="delete_account" value="SUPPRIMER">
                </form>
            </section>
            <section class="profil_droite">
                <div class="profil_case_reservation">
                    <h1>Mes réservations</h1><br/>
                    <?php
                    //RECUPERATION DES DONNEES UTILISATEURS
                    $info = $connexion->prepare("SELECT * FROM reservations WHERE id_utilisateur = $session ORDER by date_debut DESC");
                    //EXECUTION DE LA REQUETE
                    $info->execute();
                    //RECUPERATION RESULTAT
                    $info_result = $info->rowCount();
                    $info_result1 = $info->fetchAll(PDO::FETCH_ASSOC);

                    foreach($info_result1 as $id_reservations){?>
                    <p>Du <?php echo $id_reservations['date_debut']?> au <?php echo $id_reservations['date_fin']?></p>
                    <a href="facturation.php?id_reservation=<?php echo $id_reservations['id_reservation'];?>">Facture réservation n°<?php echo  $id_reservations['id_reservation']?></a><br/>
                    <?php }?> 
                </div>
                <div class="profil_case_avis">
                    <h1>Mes avis</h1><br/>
                    <table>
                        <thead>
                            <?php foreach($commentaire_result as $avis_customer){ ?>
                            <tr>
                                <th>Note</th>
                                <th>Titre</th>
                                <th>Commentaire</th>
                                <th>Date publication</th>
                                <th>Suppression</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td><?php echo $avis_customer['note_sejour'].'/5' ?></td>
                                <td><?php echo $avis_customer['titre_avis'] ?></td>
                                <td><?php echo $avis_customer['texte_avis'] ?></td>
                                <td><?php echo $avis_customer['post_date'] ?></td>
                                <td>
                                    <form action="" method="post">
                                        <button type="submit" name="delete_comment"><i class="fas fa-trash-alt"></i></button>
                                        <input type="hidden" name="hidden_id_avis" value="<?php echo $avis_customer['id_avis'] ?>">
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="profil_case_reglement">
                    <h1>Règlement intérieur</h1><br/>
                    <p>
                       Comme tous les espaces communautaires, Sardine's Camp n'échappe pas à quelques règles de vie. Consultez les afin de passer un séjour agréable. 
                    </p><br/>
                    <h2>Règles n°1</h2>
                    <p>
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                    </p>
                    <h2>Règles n°2</h2>
                    <p>
                       Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                    </p>
                    <h2>Règles n°3</h2>
                    <p>
                       Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.
                    </p>
                    <h2>Règles n°4</h2>
                    <p>
                       Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                    </p>
                    <h2>Règles n°5</h2>
                    <p>
                       Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                    </p>
                </div>
            </section>
            
            
            
            
            
        </section>



    </main>
    <footer>
        <?php include('includes/footer.php'); ?>
    </footer>
</body>

</html>
<?php
ob_end_flush();