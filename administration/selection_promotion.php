			Gestion des promotions [TERMINER 100%]
			<ul> 
				<li>
					Selection d'une promotion
					<?php
					echo Promotion::liste_promotion_for_select($promotion);
					?>
				</li>
				<li>
					<a href="?idPromotion=<?php echo $promotion; ?>&amp;page=ajoutPromotion" >Ajout d'une promotion</a>	
				</li>
			</ul>
			
			