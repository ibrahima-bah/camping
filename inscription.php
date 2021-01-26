<?php 

$page_selected = 'inscription';

?>

<!DOCTYPE html>
<html>
<head>
    <title>camping - inscription</title>
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
    include("includes/header.php") ?>
</header>
<main>
    <?php
    if (isset($_POST['submit'])) {
        $user->register(
            $_POST['firstname'],
            $_POST['lastname'],
            $_POST['email'],
            $_POST['password'],
            $_POST['conf_password'],
            $_POST['num_tel'],
            $_POST['gender']
        );
    }
    ?>
     <section id="container-register">
        <form action="inscription.php" method="post">
            <h1>INSCRIPTION</h1>
            <section id="box-form">
                <img  src="src/LOGO2.png" alt="logo-sardinescamp"/>
                <section id="box-name">
                    <section id="box-gender">
                        <input type="radio" name="gender" id="male" value="Homme">
                        <label for="male">Homme</label>
                        <input type="radio" name="gender" id="female" value="Femme">
                        <label for="female">Femme</label>
                        <input type="radio" name="gender" id="no_gender" value="Non genré">
                        <label for="no_gender">Non genré</label>
                    </section>

                    <label for="firstname">Prénom</label>
                    <input type="text" name="firstname" placeholder="Prénom">
                    <label for="lastname">Nom</label>
                    <input type="text" name="lastname" placeholder="Nom">
                    <label for="email">Email</label>
                    <input type="text" name="email" placeholder="email@email.com">
                    <label for="num_tel">N° de téléphone</label>
                    <input type="text" name="num_tel" placeholder="0123456789">
                    <label for="password">password</label>
                    <input type="password" name="password" placeholder="Mot de passe">
                    <label for="conf_password">confirmation password</label>
                    <input type="password" name="conf_password" placeholder="Confirmer mot de passe">
                </section>
            </section>
            <button type="submit" name="submit">Enregistrer vos informations</button>
        </form>
    </section>
</main>
<footer>
    <?php
    include("includes/footer.php") ?>
</footer>
</body>
</html>