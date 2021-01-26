<?php 


ob_start();

$page_selected = 'admin';
?>

<!DOCTYPE html>
<html>

<head>
    <title>camping - admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/x-icon" href="css/images/logo1.jpg">
    <link rel="stylesheet" type="text/css" href="css/style1.css">
</head>

<body>
    <header>
        <?php include("includes/header.php"); ?>
    </header>
    <main>
        


        <section class="admin_tableaux admin_table">
            <h1>GESTION DES UTILISATEURS</h1><br/>
            <?php include("tableau-utilisateurs.php"); ?>
        </section>
        
        <section class="admin_general">
            <h1>GESTION ADMINISTRATIVE</h1><br/>
            <div class="gestion_admin">
               <?php 
                include("tableau-emplacement.php");
                include("tableau-option.php");
                include("tableau-site.php");
                ?> 
            </div>
        </section>
        
        <section class="admin_tableaux admin_table">
            <h1>GESTION DES RESERVATIONS</h1><br/>
            <?php include("tableau-reservation.php");?>
        </section>
    </main>
    <footer>
        <?php include("includes/footer.php") ?>
    </footer>
</body>

</html>

<?php ob_end_flush();?>



 ?>