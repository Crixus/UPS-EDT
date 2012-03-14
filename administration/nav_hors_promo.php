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
