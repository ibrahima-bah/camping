<?php 


$page_selected = 'compte_utilisateur';
?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <title>camping - compte utilisateur</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/x-icon" href="css/images/logo1.jpg">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
<header>
    <?php
    include("includes/header.php"); ?>
</header>
<main>
    <?php

    //TENTATIVE CONNEXION BDD
    try {
        //CONNEXION BDD
        $connexion = new PDO("mysql:host=localhost;dbname=camping", 'root', 'root');
        //DEFINITION MODE ERREUR PDO SUR EXCEPTION
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //STOCKAGE ID UTILISATEUR ENVOYE DEPUIS LA PAGE ADMIN
        $id_user = $_GET['id'];
        //SELECTION DES DONNEES DE L'UTILISATEUR
        $user_profil = $connexion->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = '$id_user' ");
        //EXECUTION REQUETE
        $user_profil->execute();
        //RECUPERATION RESULTAT
        $user_profil_result = $user_profil->fetchAll(PDO::FETCH_ASSOC);


        if (isset($_POST['delete'])) {
            if ($user_profil_result[0]['avatar'] != null) {
                $avatar_delete = null;
                //SUPPRESSION AVATAR EN BDD
                $delete_avatar = "UPDATE utilisateurs SET avatar=:avatar WHERE id_utilisateur = '$id_user' ";
                //PREPARATION REQUETE
                $delete1 = $connexion->prepare($delete_avatar);
                $delete1->bindParam(':avatar', $avatar_delete, PDO::PARAM_NULL);
                //EXECUTION REQUETE
                $delete1->execute();
                header('location:cpte_users.php?id='.$id_user.'');
            }
        }


        //MODIFICATION DES DONNEES DE L'UTILISATEUR SI ON APPUIS SUR VALIDER
        if (isset($_POST['submit'])) 
        {
            //DEFINITION DES VARIABLES STOCKANT LES DONNEES UTILISATEURS

            $mail = $_POST['mail'];
            $phone = $_POST['phone_number'];
            $password = $_POST['password'];
            $check_password = $_POST['check_password'];
            $hash = password_hash($password, PASSWORD_BCRYPT, array('cost' => 10));

       
            
            if(!empty($mail))
                            {
                                
                                $recup_mail_bdd= $connexion->prepare("SELECT * FROM utilisateurs WHERE email='$mail'");
                                $recup_mail_bdd ->execute();
                                $result_mail= $recup_mail_bdd->rowCount();
                                
                                
                                    if($mail != $user_profil_result[0]['email'] AND $result_mail>=1)
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
                                    $update_mail = "UPDATE utilisateurs SET email=:mail WHERE id_utilisateur = $id_user ";
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
                                $update_phone = "UPDATE utilisateurs SET num_tel=:phone WHERE id_utilisateur = $id_user ";
                                //PREPARATION REQUETE
                                $update_niv3 = $connexion -> prepare($update_phone);
                                $update_niv3->bindParam(':phone',$phone, PDO::PARAM_STR);
                                //EXECUTION REQUETE
                                $update_niv3->execute();
                                    
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
                                $update_password = "UPDATE utilisateurs SET password=:hash WHERE id_utilisateur = $id_user ";
                                //PREPARATION REQUETE
                                $update_niv5 = $connexion->prepare($update_data_user);
                                $update_niv5->bindParam(':hash', $hash, PDO::PARAM_STR);
                                //EXECUTION REQUETE
                                $update_niv5->execute();

                                }
                            }
            

                            if (!empty($errors))
                            {
                                $message = new messages($errors);
                                echo $message->renderMessage();
                            }
                            else 
                            {
                                header ('location:cpte_users.php?id='.$id_user.'');
                            }
        }
        
         //RECUPERATION DES COMMENTAIRES DE L'UTILISATEUR
             $commentaire=$connexion->prepare("SELECT * FROM commentaires WHERE id_utilisateur = $id_user ORDER BY post_date DESC");
             //EXECUTION DE LA REQUETE
             $commentaire->execute();
             //RECUPERATION RESULTAT
             $commentaire_result = $commentaire->fetchAll(PDO::FETCH_ASSOC);

             //SUPPRESION D'UN COMMENTAIRE
             if(isset($_POST['delete_comment']))
             {
             $id_avis=$_POST['hidden_id_avis'];

             $delete_com=$connexion->prepare("DELETE FROM commentaires WHERE id_avis = $id_avis");
             //EXECUTION DE LA REQUETE
             $delete_com->execute();
             header('location:cpte_users.php?id='.$id_user.'');
             }

        
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }


    ?>
    

    <section class="profil_section1">     
            <form action="" method="post" enctype="multipart/form-data" class="avatar profil_case_avatar" id="avatar_profil">
                <img src="
                             <?php
                              if($user_profil_result[0]['avatar'] == NULL){
                                  echo 'images/no-image.png';
                              }else{echo $user_profil_result[0]['avatar'];}
                              ?>" alt="avatar" width="300" height="300"><br/>
                
                <h2><?php echo $user_profil_result[0]['prenom'].' '.$user_profil_result[0]['nom'] ?></h2><br />
                <div class="validation_avatar">
                    <button type="submit" name="delete"><i class="fas fa-trash-alt"></i></button>
                </div>
                
            </form>
        </section>
    
        
        <section class="profil_section2">
            <section class="profil_modif">
                <form action="" method="post" class="profil_case_modification">
                    <h2>Modification des données de l'utilisateur</h2><br />
                    <?php

                        $gender_check = $user_profil_result[0]['gender'];
                        $check = ($gender_check=="Femme")?true:false;
                        $check2 = ($gender_check=="Homme")?true:false;
                        $check3 = ($gender_check=="Non genré")?true:false;
                        ?>

                    <input type="radio" name="gender" value="Femme" <?php if($check==true){echo "checked";}else{echo "";}  ?> disabled/>

                    <label for="female">Femme</label>

                    <input type="radio" name="gender" value="Homme" <?php if($check2==true){echo "checked";}else{echo "";} ?> disabled/>
                    <label for="male">Homme</label>

                    <input type="radio" name="gender" value="Non genré" <?php if($check3==true){echo "checked";}else{echo "";} ?> disabled/>
                    <label for="no_gender">Non genré</label><br />

                    <label for="name">Nom</label><br />
                    <input type="text" name="lastname" value="<?php echo $user_profil_result[0]['nom'] ?>" disabled><br />
                    <label for="firstname">Prénom</label><br />
                    <input type="text" name="firstname" value="<?php echo $user_profil_result[0]['prenom'] ?>" disabled><br />

                    <label for="mail">Email</label><br />
                    <input type="mail" name="mail" value="<?php echo $user_profil_result[0]['email'] ?>"><br />
                    <label for="phone_number">Numéro de téléphone</label><br />
                    <input type="text" name="phone_number" value="<?php echo $user_profil_result[0]['num_tel'] ?>"><br />

                    <label for="password">Mot de passe</label><br />
                    <input type="password" name="password" placeholder="Entrez votre nouveau mot de passe"><br />
                    <label for="password">Confirmation de mot de passe</label><br />
                    <input type="password" name="check_password" placeholder="Confirmez votre nouveau mot de passe"><br /><br />

                    <input type="submit" name="submit" value="VALIDER"><br />
                </form>
                <div class="profil_case_reservation ">
                    <h1>Réservations</h1><br/>
                    <?php
                    //RECUPERATION DES DONNEES UTILISATEURS
                    $info = $connexion->prepare("SELECT * FROM reservations WHERE id_utilisateur = $id_user ORDER by date_debut DESC");
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
                <div class="profil_case_avis ">
                    <h1>Commentaires de l'utilisateur</h1><br/>
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
            
            </section>
        </section>
    
</main>
<footer>
    <?php
    include("includes/footer.php") ?>
</footer>
</body>

</html>
