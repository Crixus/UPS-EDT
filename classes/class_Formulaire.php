<?php
	/**
	 * Supporté : Input (text, password, submit), Textarea
	 * */
	class Formulaire{
		private $idFormulaire;
		private $legend;
		private $champs;
		
		public function Formulaire($idFormulaire, $legend, $champs, $action = ""){
			$this->idFormulaire = $idFormulaire;	
			$this->legend = $legend;	
			$this->champs = $champs;	
		}
		
		public static function testEnvoi($nameSubmit){
			return isset($_POST[$nameSubmit]);		
		}
		
		public function toFormHtml($nbTabs = 0){
			$tab = ""; while($nbTabs > 0){ $tab .= "\t"; $nbTabs--;}
			echo "$tab<form id=\"$this->idFormulaire\" method=\"post\" class=\"formulaire\">\n";
			echo "$tab\t<fieldset>\n";
			if($this->legend != null){ echo "$tab\t\t<legend>".$this->legend."</legend>\n"; }
			echo "$tab\t\t<ul>\n";
			foreach($this->champs as $champ){
				$champ_name = $champ['name'];
				$champ_balise = $champ['balise'];
				echo "$tab\t\t\t<li>\n";
				if($champ_balise == "input" && $champ['type'] == "submit"){ 
					echo "$tab\t\t\t\t<label for=\"$champ_name\"></label>\n";
				}
				else{
					if(isset($champ['isRequired']) && $champ['isRequired'] == true){
						$etoile = " <span class=\"etoile\">*</span>";
					}
					else{
						$etoile = "";
					}
					$legend = str_replace("_", " ", $champ_name.$etoile);
					echo "$tab\t\t\t\t<label for=\"$champ_name\">$legend</label>\n";
				}
				switch($champ_balise){
					case "input":
						$champ_type = $champ['type'];
						echo "$tab\t\t\t\t<input type=\"$champ_type\" ";
						if(isset($champ['name'])){ echo "name=\"".$champ['name']."\" ";}
						if(isset($champ['value']) && $champ['value'] != ''){ echo "value=\"".$champ['value']."\" ";}
						if(isset($champ['size'])){ echo "size=\"".$champ['size']."\" ";}
						if(isset($champ['placeholder'])){ echo "placeholder=\"".$champ['placeholder']."\" ";}
						if(isset($champ['isRequired']) && $champ['isRequired'] == true){ echo "required ";}
						echo "/>\n";
						break;
					case "textarea":
						echo "$tab\t\t\t\t<textarea ";
						if(isset($champ['name'])){ echo "name=\"".$champ['name']."\" ";}
						if(isset($champ['rows'])){ echo "rows=\"".$champ['rows']."\" ";}
						if(isset($champ['cols'])){ echo "cols=\"".$champ['cols']."\" ";}
						echo ">";
						if(isset($champ['value']) && $champ['value'] != ''){ echo $champ['value'];}
						echo "</textarea>\n";
						break;
				}
				echo "$tab\t\t\t</li>\n";
			}
			echo "$tab\t\t</ul>\n";
			echo "$tab\t</fieldset>\n";
			echo "$tab</form>\n";
		}		
	}
