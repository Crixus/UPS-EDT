				<h1>Gestion affichage</h1>
				<ul>
					<li>
						<a href="?page=styleTypeCours<?php if(isset($_GET['idPromotion'])){?>&amp;idPromotion=<?php echo $_GET['idPromotion']; }?>" >Ajout d'une couleur d'un type de cours </a>
					</li>
				</ul>
				
				<h1>Gestion des salles</h1>
				<ul>
					<li>
						<a href="?page=ajoutBatiment<?php if(isset($_GET['idPromotion'])){?>&amp;idPromotion=<?php echo $_GET['idPromotion']; }?>" >Ajout d'un batiment</a>
					</li>
					<li>
						<a href="?page=ajoutSalle<?php if(isset($_GET['idPromotion'])){?>&amp;idPromotion=<?php echo $_GET['idPromotion']; }?>" >Ajout d'une salle</a>
					</li>
					<li>
						<a href="?page=ajoutTypeSalle<?php if(isset($_GET['idPromotion'])){?>&amp;idPromotion=<?php echo $_GET['idPromotion']; }?>" >Ajout d'un type de salle</a>
					</li>
				</ul>
				
				<h1>Gestion des intervenants</h1>
				<ul>
					<li>
						<a href="?page=ajoutIntervenant<?php if(isset($_GET['idPromotion'])){?>&amp;idPromotion=<?php echo $_GET['idPromotion']; }?>" >Ajout d'un intervenant</a>
					</li>
				</ul>
