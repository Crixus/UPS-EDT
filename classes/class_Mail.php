<?php
	/**
	 * Classe d'envoi d'email
	 */
	class Mail {
		
		public static function arrayEmailToString($array) {
			$nombreEmails = sizeof($array);
			$string = "";
			for ($i = 0; $i < $nombreEmails; $i++) {
				$string .= $array[$i];
				if ($i != ($nombreEmails -1)) {
					$string .= ", ";
				}
			}
			return $string;
		}

		public static function envoyer_email($sujet, $contenu, $expediteur, $destinataires, $cc_destinataires, $bcc_destinataires) {
			$to = Mail::arrayEmailToString($destinataires);
			$cc = Mail::arrayEmailToString($cc_destinataires);
			$bcc = Mail::arrayEmailToString($bcc_destinataires);
			$subject = $sujet;
			$headers = "From: " . $expediteur . "\r\n".
					   "Cc: " . $cc . "\r\n".
					   "Bcc: " . $bcc . "\r\n".
					   "Reply-To: " . $expediteur . "\r\n".
					   "X-Mailer: PHP/" . phpversion() . "\r\n".
					   "Content-Type: text/plain; charset=\"UTF-8\"\r\n"; 
			$message = $contenu;
			return mail($to, $subject, $message, $headers);
		}
		
		public static function envoyer_creation_utilisateur($mail, $login, $mot_de_passe) {
			$sujet = "UPS-EDT - Connexion";
			$destinataires = Array($mail);
			$cc_destinataires = Array();
			$bcc_destinataires = Array();
			$message = "Bonjour, \r\n\r\n".
					   "Votre compte UPS-EDT à été créé / modifié\r\n".
					   "Votre login : " . $login . "\r\n".
					   "Votre mot de passe : " . $mot_de_passe . "\r\n";
			return Mail::envoyer_email($sujet, $message, "ups-edt@ups-tlse3.com", $destinataires, $cc_destinataires, $bcc_destinataires);			
		}
		
		public static function envoyer_modification_motDePasse_utilisateur($Utilisateur, $mot_de_passe) {
			switch ($Utilisateur->getType()) {
				case "Etudiant":
					$Destinataire = new Etudiant($Utilisateur->getIdCorrespondant());
					break;
				case "Intervenant":
					$Destinataire = new Intervenant($Utilisateur->getIdCorrespondant());
					break;
				default:
					return false;
			}
			$sujet = "Modification mot de passe Utilisateur UPS-EDT";
			$destinataires = Array($Destinataire->getEmail());
			$cc_destinataires = Array();
			$bcc_destinataires = Array();
			$message = "Bonjour, \r\n\r\n".
					   "Votre mot de passe UPS-EDT à été modifié : \r\n".
					   "Votre login : " . $Destinataire->getLogin() . "\r\n".
					   "Votre mot de passe : " . $mot_de_passe . "\r\n";
			return Mail::envoyer_email($sujet, $message, "ups-edt@ups-tlse3.com", $destinataires, $cc_destinataires, $bcc_destinataires);			
		}
	}
