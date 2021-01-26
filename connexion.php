<?php 

$page_selected = 'connexion';

?>


<!DOCTYPE html>
<html>
<head>
	<title>Camping-connexion</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/x-icon" href="css/images/logo1.jpg">
    <link rel="stylesheet" type="text/css" href="css/style1.css">
</head>
<body>
	<header>
		<?php 
		include("includes/header.php");?>
	</header>
	<main>
		<?php 
		if (isset($_POST['submit'])) 
		{
			$user->connect($_POST['email'], $_POST['password']);
		}

		?>
		<section id="connect">
			<section id="connect-form">
				<section id="title-connect">
					<h1>Bienvenue Au Camping Les Sardines!</h1>
				</section>	
			
				<form id="login" action="connexion.php" method="post">
				<label for="email">email:</label>
				<input type="text" name="email" placeholder="email@email.com" required="">
				<label for="email">Password:</label>
				<input type="text" name="password" placeholder="Mot de passe" required="">
				<button type="submit" name="submit">Connexion</button>

				</form>

				<a  id="linkconnect "href="inscription.php">Pas encore inscrit? Merci de nous rejoindre!</a>
			</section>
		</section>
	</main>
	<footer>
		<?php include("includes/footer.php") ;?>
	</footer>
</body>
</html>