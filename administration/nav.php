					<h1>Gestion affichage</h1>
					<ul>
						<li>
							<a href="?page=styleTypeCours<?php if(isset($_GET['idPromotion'])){?>&amp;idPromotion=<?php echo $_GET['idPromotion']; }?>" >Gestion des couleurs de type de cours</a>
						</li>
					</ul>
					
					<h1>Gestion des salles</h1>
					<ul>
						<li>
							<a href="?page=ajoutBatiment<?php if(isset($_GET['idPromotion'])){?>&amp;idPromotion=<?php echo $_GET['idPromotion']; }?>" >Gestion des bâtiments</a>
						</li>
						<li>
							<a href="?page=ajoutSalle<?php if(isset($_GET['idPromotion'])){?>&amp;idPromotion=<?php echo $_GET['idPromotion']; }?>" >Gestion des salles</a>
						</li>
						<li>
							<a href="?page=ajoutTypeSalle<?php if(isset($_GET['idPromotion'])){?>&amp;idPromotion=<?php echo $_GET['idPromotion']; }?>" >Gestion des types de salles</a>
						</li>
					</ul>
					
					<h1>Gestion des intervenants</h1>
					<ul>
						<li>
							<a href="?page=ajoutIntervenant<?php if(isset($_GET['idPromotion'])){?>&amp;idPromotion=<?php echo $_GET['idPromotion']; }?>" >Gestion des intervenants</a>
						</li>
					</ul>
					<h1>Gestion des cours</h1>
					<ul>
						<li>
							<a href="?page=ajoutTypeCours<?php if(isset($_GET['idPromotion'])){?>&amp;idPromotion=<?php echo $_GET['idPromotion']; }?>" >Gestion des types de cours</a>
						</li>
<?php 
	if(isset($_GET['idPromotion'])){
?>
						<li>
							<a href="?page=ajoutCours&amp;idPromotion=<?php echo $_GET['idPromotion']; ?>" >Ajout d'un cours</a>
						</li>
<?php
	}
?>
					</ul>
<?php 
	if(isset($_GET['idPromotion'])){
?>				
					<h1>Gestion des spécialités</h1>
					<ul>
						<li>
							<a href="?page=ajoutSpecialite&amp;idPromotion=<?php echo $_GET['idPromotion']; ?>" >Ajout d'une spécialité</a>
						</li>
					</ul>
<?php
	}
?>			
					<h1>Gestion des UE</h1>
					<ul>
						<li>
							<a href="?page=ajoutUE<?php if(isset($_GET['idPromotion'])){?>&amp;idPromotion=<?php echo $_GET['idPromotion']; }?>">Ajout d'une UE</a>
						</li>
<?php
	if(isset($_GET['idPromotion'])){
?>	
						<li>
							<a href="?page=listeInscriptionsUE&amp;idPromotion=<?php echo $_GET['idPromotion']; ?>" >Inscriptions des étudiants aux UE</a>
						</li>
<?php
	}
?>
					</ul>

					<h1>Gestions des étudiants</h1>
					<ul>
						<li>
							<a href="?page=ajoutEtudiant&amp;idPromotion=<?php echo $_GET['idPromotion']; ?>" >Ajout d'un étudiant</a>
						</li>
					</ul>
						
					<h1>Gestions des Groupes de Cours</h1>
					<ul>
						<li>
							<a href="?page=ajoutGroupeCours&amp;idPromotion=<?php echo $_GET['idPromotion']; ?>" >Ajout d'un groupe de cours</a>
						</li>
						<li>
							<a href="?page=gestionGroupeCours&amp;idPromotion=<?php echo $_GET['idPromotion']; ?>" >Gestion des groupes de cours </a>
						</li>
					</ul>
					
					<h1>Gestion des Groupes d'Etudiants</h1>
					<ul>					
						<li>
							<a href="?page=ajoutGroupeEtudiants&amp;idPromotion=<?php echo $_GET['idPromotion']; ?>" >Ajout d'un groupe d'étudiant</a>
						</li>
						<li>
							<a href="?page=gestionGroupeEtudiants&amp;idPromotion=<?php echo $_GET['idPromotion']; ?>" >Gestion des groupes d'étudiants </a>
						</li>
					</ul>

					<h1>Gestion des publications</h1>
					<ul>
						<li>
							<a href="?page=gestionPublication&amp;idPromotion=<?php echo $_GET['idPromotion']; ?>" >Gestion de la publication</a>
						</li>
					</ul>