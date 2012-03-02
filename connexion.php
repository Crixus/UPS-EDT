<?php
	// Informations de base de données
	include_once('./includes/infos_bdd.php');
	
	include_once('./classes/class_Utilisateur.php');
	include_once('./classes/class_Intervenant.php');
	
	session_start();
		
	if(isset($_POST['Validation_Connexion'])){
		$login = $_POST['Identifiant'];
		$motDePasse = $_POST['Mot_de_passe'];
		
		if($idUtilisateur = Utilisateur::identification($login, $motDePasse)){
			$Utilisateur = new Utilisateur($idUtilisateur);
			$_SESSION['idUtilisateur'] = $idUtilisateur;
			$_SESSION['Type'] = $Utilisateur->getType();
			header('Location: ./index.php');
		}
		else{
			// A REFAIRE
			echo "<p>Login ou pass incorrect</p>";
		}
	}
?>
<!DOCTYPE html>
	<head>
		<title>UPS-Timetable Connexion</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="./css/style.css" />
	</head>
	<body>	
		<form id="formulaireConnexion" method="post">
			<fieldset>
				<legend>Connexion</legend>
				<ol>
					<li>
						<label for="Identifiant">Identifiant</label>
						<input type="text" name="Identifiant" required />
					</li>
					<li>
						<label for="Mot_de_passe">Mot de passe</label>
						<input type="password" name="Mot_de_passe" required />
					</li>
					<li>
						<label for="Validation_Connexion"></label>
						<button type="submit" name="Validation_Connexion">Se connecter</button>
					</li>
				</ol>
			</fieldset>
		</form>
	</body>
</html>
