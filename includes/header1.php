<?php

require 'classes/utilisateurs.php';
require 'classes/proprietaire.php';

session_start();
$db = new Database();
$user = new users($db);
$user->refresh();

if (isset($_POST["deco"])) {
    $user->disconnect();
}

?>
<header>
    <section id="top-header1">
        <a href="#newsletter">RECEVOIR NOTRE CATALOGUE</a>
        <?php
        if (isset($_SESSION['user']['is_admin']) AND  $_SESSION['user']['is_admin'] == 1) {
            ?>
            <li><a href="admin.php">TABLEAU DE BORD</a></li>
            <?php
        } ?>
        <a id="header-title" href="reservation-form.php">RESERVER</a>
    </section>
    <section>
        <nav>
            <ul id="nav_links1">
                <li><a href="planning.php">NOS DISPONIBILITÉS</a></li>
                <li><a href="vue-commentaires.php">LES AVIS</a></li>
                <a id="top-logo" href="index.php">
                    <img id="logo1" src="https://i.ibb.co/hMhFxXF/logotype1.png" alt="logotype1">
                    <h1>HORIZON CAMPING</h1>
                </a>
                </li>
                <?php
                if (isset($_SESSION["user"])){
                    ?>
                    <li><a href="profil.php">MON COMPTE</a></li>
                    <form action="index.php" method="post">
                        <input id="deco" name="deco" value="DECONNEXION" type="submit"/>
                    </form>
                    <?php
                }
                else{
                ?>
                <li><a  href="mailto:hello@sardinescamp.com">NOUS CONTACTER</a></li>
                <li><a  href="connexion.php"> SE CONNECTER</a></li>
            </ul>

            <?php
            } ?>

        </nav>
    </section>
</header>