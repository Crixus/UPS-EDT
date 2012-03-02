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
		<link rel="stylesheet" href="./css/style.php" />
	</head>
	<body>	
		<div id="pageConnexion">
			<h1>Emploi du temps - Université Paul Sabatier</h1>
			<form id="formulaireConnexion" method="post">
				<fieldset>
					<legend>Connexion</legend>
					<div id="imageLogoConnexion">
						<img src="./images/logo_UPS.jpg" alt="Logo université Toulouse 3" />
					</div>
					<div id="indications">
						<p>Veuillez entrer :</p>
					</div>
					<table>
						<tr>
							<td><label for="Identifiant">Identifiant</label></td>
							<td><input type="text" name="Identifiant" required /></td>
						</tr>
						<tr>
							<td><label for="Mot_de_passe">Mot de passe</label></td>
							<td><input type="password" name="Mot_de_passe" required /></td>
						</tr>
						<tr>
							<td><label for="Validation_Connexion"></label></td>
							<td><button type="submit" name="Validation_Connexion">Se connecter</button></td>
						</tr>
					</table>
				</fieldset>
			</form>
			<div id="bas">
				<p>Manuel Utilisateur</p>
			</div>
		</div>
	</body>
</html>
