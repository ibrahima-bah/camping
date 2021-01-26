<?php

$page_selected = 'index';
?>
<!DOCTYPE html>
<html>
<head>
    <title>camping - homepage</title>
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
    include("includes/header1.php");
    $connexion = $db->connectDb();
    include 'commentaires.php'; ?>
</header>
<main>
    <section id="banner">
        <h1>WELCOME</h1>
        <img id="logo" src="src/wave-white.png" alt="icon-waves">
        <h2>une ambiance conviviale, des activités variées, et un cadre idyllique... bienvenue au Sardine's Camp</h2>
        <a href="reservation-form.php">Réserver votre séjour</a>
    </section>

    <section id="description">

        <h1>NOS SITES</h1>

        <section id="presentation-text">
            <article>Nous vous accueillons sur notre site dans des lieux idylliques. Ambiance plage, pins ou maquis, vous trouverez forcément le cadre idéal pour passer des vacances 
            bohêmes au bord de la Méditerranée. Rejoignez-nous dès maintenant et choisissez l'ambiance qui
            vous correspond!
             </article>
            <aside>
                <script charset='UTF-8' src='http://www.meteofrance.com/mf3-rpc-portlet/rest/vignettepartenaire/130550/type/VILLE_FRANCE/size/PAYSAGE_VIGNETTE' type='text/javascript'></script>
            </aside>
        </section>

        <section id="sites">

            <article>
                <div class="lieu">
                    <h2>la plage</h2>
                    <img src="src/wave.png" alt="wave-icon-white">
                </div>
                <div class="lieu">
                    <h2>les goudes</h2>
                    <img src="src/pins.png" alt="pine-icon-white">
                </div>
                <div class="lieu">
                    <h2>terrasse</h2>
                    <img id="massif" src="src/massif.png" alt="mountain-icon-white">
                </div>
            </article>

            <section id="diapo">
                <ul class="slideshow1">
                    <li><span><div class="principal1"></div></span></li>
                    <li><span><div class="principal1"></div></span></li>
                    <li><span><div class="principal1"></div></span></li>
                    <li><span><div class="principal1"></div></span></li>
                    <li><span><div class="principal1"></div></span></li>
                </ul>
            </section>

        </section>

    </section>

    <section id="services">
        <h1>NOS SERVICES</h1>

        <section class="cen">
            <section class="service">
                <i class="fas fa-plug"></i>
                <h2>borne électrique</h2>
                <p>des bornes electriques sont installées à travers tout le camping pour recherger et profiter de tous
                    vos appareils électriques</p>
            </section>

            <section class="service">
                <i class="fas fa-wifi"></i>
                <h2>wifi</h2>
                <p>un réseau wifi avec un débit ultra-performant vous permet de profiter d'une super connexion quelque
                    soit votre emplacement</p>
            </section>

            <section class="service">
                <i class="fas fa-shower"></i>
                <h2>sanitaires</h2>
                <p>nos sanitaires haut de gamme sont nettoyés tout au long de la journée et vous offrent un confort
                    quelque soit le moment de la journée</p>
            </section>

            <section class="service">
                <i class="fas fa-swimmer"></i>
                <h2>activités</h2>
                <p>inscrivez-vous à nos activités menées par des coachs expérimentés : yoga, frisbee, ski nautique...
                    amusez-vous et dépensez vous !</p>
            </section>

            <section class="service">
                <i class="fas fa-coffee"></i>
                <h2>coffee shop et snacks bio</h2>
                <p>notre coffee shop vous propose un service de boissons chaudes et de snacks préparés avec des produits
                    locaux bios toute la journée</p>
            </section>
        </section>
    </section>

    <section id="avis_index">
        <h1>LES 3 DERNIERS AVIS</h1>
        <section id="last_avis_index">
            <?php
            $q = $connexion->prepare("SELECT id_avis FROM avis ORDER BY id_avis DESC LIMIT 3");
            $q->execute();
            $avis = $q->fetchAll();
            foreach ($avis as $value) {
                viewComment($value['id_avis']);
            } ?>
        </section>
        <div>
            <a href="vue-commentaires.php">En voir plus ...</a>
        </div>
    </section>

</main>
<footer>
    <?php
    include("includes/footer.php") ?>
</footer>
</body>
</html>