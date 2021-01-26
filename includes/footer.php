<?php

$user->redirect($page_selected);

?>
<footer>
    <nav>
        <ul>
            <li><a href="index.php">ACCUEILPAGE</a></li>
            <?php
            try {
                $bdd = new PDO('mysql:host=localhost;dbname=camping;charset=utf8', 'root', 'root');
                $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }

            if (isset($_SESSION['user']['id_user'])) {
                if ($_SESSION['user']['is_admin'] == 1) {
                    ?>

                    <li><a href="admin.php">espace admin</a></li>

                    <?php
                }
                ?>
                <li><a href="planning.php">nos disponibilités</a></li>
                <li><a href="mailto:hello@sardinescamp.com">nous contacter</a></li>
                <li>
                    <form action="index.php" method="post">
                        <input id="deco1" name="deco" value="DECONNEXION" type="submit"/>
                    </form>
                </li>

                <?php
            } else {
                ?>
                <li><a href="connexion.php">réserver</a></li>
                <li><a href="inscription.php">s'inscrire</a></li>
                <li><a href="connexion.php">se connecter</a></li>
            <?php
            } ?>
        </ul>
    </nav>
    <section id=container-bottom>
        <section id="footerleft">
            <section id="newsletter">
                <h3>inscris-toi à notre newsletter</h3>
                <?php
                if (isset($_POST['submitnews'])) {
                    //include 'contact-form.php';
                }
                ?>
                <form action="" method="POST">
                    <input type="text" id="news" name="emailnews" placeholder="Enter your email"></br>
                    <input type="submit" name="submitnews" id="btnnews" value="envoyer">
                </form>
            </section>
        </section>

        <section id="footercenter">
            <section id="bottom-logo">
                <img id="logo" src="https://i.ibb.co/XzyCCqt/LOGO1.png" alt="logo-sardinescamp">
                <h1><a href="index.php">CAMPING DES SARDINES</a></h1>
            </section>

            <section id="social-media">
                <ul id="social-list">
                    <li><a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a></li>
                    <li><a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a></li>
                    <li><a href="https://www.linkedin.com" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                </ul>
            </section>

            <p id="copyright">&copy; 2020 SARDINE'S CAMPING</p>
        </section>

        <section id="footerright">
            <h2>SARDINE'S CAMP</h2>
            <p>
            </p>
            <address>
                8 rue d'Aubagne
                <a class="contact" href="tel:+330499999999">"tel:+330499999999"</a>
                <a class="contact" href="mailto:hello@sardinescamp.com">hello@sardinescamp.com</a>
            </address>
        </section>
    </section>
</footer>