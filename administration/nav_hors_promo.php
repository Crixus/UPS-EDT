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
							<a href="page=ajoutTypeCours&amp;<?php if(isset($_GET['idPromotion'])){?>&amp;idPromotion=<?php echo $_GET['idPromotion']; }?>" >Gestion des types de cours</a>
						</li>
<?php 
	if(isset($_GET['idPromotion'])){
?>
						<li>
							<a href="?idPromotion=<?php echo $_GET['idPromotion']; ?>&amp;page=ajoutCours" >Ajout d'un cours</a>
						</li>
<?php
	}
?>
					</ul>
					
					<h1>Gestion des spécialités</h1>
					<ul>
						<li>
							<a href="?idPromotion=<?php echo $_GET['idPromotion']; ?>&amp;page=ajoutSpecialite" >Ajout d'une spécialité</a>
						</li>
					</ul>
					
					<h1>Gestion des UE</h1>
					<ul>
						<li>
							<a href="?idPromotion=<?php echo $_GET['idPromotion']; ?>&amp;page=ajoutUE" >Ajout d'une UE</a>
						</li>
						<li>
							<a href="?idPromotion=<?php echo $_GET['idPromotion']; ?>&amp;page=listeInscriptionsUE" >Inscriptions des étudiants aux UE</a>
						</li>
					</ul>

					<h1>Gestions des Etudiants</h1>
					<ul>
						<li>
							<a href="?idPromotion=<?php echo $_GET['idPromotion']; ?>&amp;page=ajoutEtudiant" >Ajout d'un étudiant</a>
						</li>
					</ul>
						
					<h1>Gestions des Groupes de Cours</h1>
					<ul>
						<li>
							<a href="?idPromotion=<?php echo $_GET['idPromotion']; ?>&amp;page=ajoutGroupeCours" >Ajout d'un groupe de cours</a>
						</li>
						<li>
							<a href="?idPromotion=<?php echo $_GET['idPromotion']; ?>&amp;page=gestionGroupeCours" >Gestion des groupes de cours </a>
						</li>
					</ul>
					
					<h1>Gestion des Groupes d'Etudiants</h1>
					<ul>					
						<li>
							<a href="?idPromotion=<?php echo $_GET['idPromotion']; ?>&amp;page=ajoutGroupeEtudiants" >Ajout d'un groupe d'étudiant</a>
						</li>
						<li>
							<a href="?idPromotion=<?php echo $_GET['idPromotion']; ?>&amp;page=gestionGroupeEtudiants" >Gestion des groupes d'étudiants </a>
						</li>
					</ul>

					<h1>Gestion des publications</h1>
					<ul>
						<li>
							<a href="?idPromotion=<?php echo $_GET['idPromotion']; ?>&amp;page=gestionPublication" >Gestion de la publication</a>
						</li>
					</ul>
