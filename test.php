<?php
	// Informations de base de données
	include_once('./includes/infos_bdd.php');
	
	// Création des tables (phase de deploiement / tests)
	define('CREER_TABLES', true);
	
	include_once('./classes/class_Utils_SQL.php');
	
	$repertoire = opendir("./classes/");
	while($fichier = readdir($repertoire)){
		if($fichier != '..' && $fichier != '.'){
			include_once("./classes/$fichier");
		}
	}
	
	if(CREER_TABLES){
		Utils_SQL::sql_from_file("./sql/AllCreates.sql");
		Utils_SQL::sql_from_file("./sql/AllInserts.sql");
	}
	
	if(Formulaire::testEnvoi('Connexion_valider')){
		// Le formulaire à été saisie, on verifis, on authentifie...
		$login = $_POST['Identifiant'];
		$motDePasse = $_POST['Mot_de_passe'];
		if($idEtudiant = Etudiant::identification($login, $motDePasse)){
			echo "<p>Login et pass correct</p>";
			$Utilisateur = new Etudiant($idEtudiant);
		}
		else{
			echo "<p>Login ou pass incorrect</p>";
		}
	}
	
	// Valeurs par défaut formulaire
	if(isset($_POST['Identifiant'])){ $login = $_POST['Identifiant']; }else{ $login = ""; }
	if(isset($_POST['Mot_de_passe'])){ $motDePasse = $_POST['Mot_de_passe']; }else{ $motDePasse = ""; }
	
	$formulaireConnexion = new Formulaire(
		"Connexion", // Id formulaire
		"Connexion", // Legende
		Array(		 // Champs
			Array(
				'balise' => 'input',
				'type' => 'text',
				'name' => 'Identifiant',
				'size' => '30',
				'value' => $login,
				'isRequired' => true
			),
			Array(
				'balise' => 'input',
				'type' => 'password',
				'name' => 'Mot_de_passe',
				'size' => '30',
				'value' => $motDePasse,
				'isRequired' => true
			),
			Array(
				'balise' => 'input',
				'type' => 'submit',
				'name' => 'Connexion_valider',
				'value' => 'Se connecter'
			)
		),
		""		// Action (ici on rappele la même page
	);
?>
<!DOCTYPE html>
	<head>
		<title>UPS TimeTable</title>
		<meta charset="utf-8" />
	</head>
	<body>
		<h1>Ups TimeTable</h1>
		<?php $formulaireConnexion->toFormHtml(); ?>
<?php
	if(isset($Utilisateur)){
		echo $Utilisateur->toUl();		
		$listeIdCours = V_Cours_Etudiants::liste_idCours_Un_Etudiants($Utilisateur->getId());
		foreach($listeIdCours as $idCours){
			$Cours = new V_Infos_Cours($idCours);
			echo $Cours->toString()."<br />";	
		}
	}
?>
	</body>
</html>
