<?php

	function afficherAccueil()
	{
		$contenuErr = '';
		require_once("vue/gabaritLogin.php");

	}

	function afficherErreurLogin($erreur)
	{
		$contenuErr = '<p id="erreur">' . $erreur . '</p>';
		require_once("vue/gabaritLogin.php");
	}

	function afficherAccueilAgent($employe)
	{
		$header = '<form action="main.php" method="post"><p>' . $_SESSION['empl']->nomEmploye .
			'<input type="submit" name="deco" value="Déconnexion"/></p></form>';
		$contenu = '<form id="gestionfin" action="main.php" method="post">
    	<fieldset><legend>Gestion financière</legend>
        <p>
            <label>Id client</label><input type="number" name="idclientGF" required/>
        </p>
        <p><input type="submit" name="gestionF"/></p>';

		$contenu .= afficherErreur('erreurId');

		$contenu .= '</fieldset></form><form id="monForm2" action="main.php" method="post">
	<fieldset>
		<legend>Synthèse</legend>
		<p>
			<label>id Client</label><input type="number" name="idclient" required/>
		</p>

		<p>
			<input type="submit" name="synthese" value="Synthèse client"/>
		</p>

	</fieldset>
	</form>
	<form id="rechercheID" action="main.php" method="post"/>
	<fieldset>
	<legend>Rechercher id client</legend>
	<p>
	    <label>Nom client</label><input type="text" name="nomclient" required/>
    </p>
    <p>
        <label>Date de naissance</label><input type="date" name="dateNaiss" required/>
    </p>
    <p><input type="submit" name="rechercheID" value="Rechercher"/></p>';
		if (!empty($_SESSION['rechercheIdClient'])) {
			$contenu .= '
	<p>
	    Client : ' . $_SESSION['rechercheIdClient']->nom .
				' Identifiant : ' . $_SESSION['rechercheIdClient']->idClient . '
    </p>';
			unset($_SESSION['rechercheIdClient']);
		}
		$contenu .= afficherErreur('erreurClient');

		$contenu .= '</fieldset>
	</form>';

		require_once("vue/gabarit.php");

	}

	function afficherSynthese($client)
	{
		$header = '<form action="main.php" method="post"><p>' . $_SESSION['empl']->nomEmploye .
			'<input type="submit" name="accueil" value="Accueil"/>
		<input type="submit" name="deco" value="Déconnexion"/></p></form>';

		$contenu = '';

		$contenu .= '<form action="main.php" method="post">
				<input name="nom" type = "text" value = "' . $client->nom . '" />
				<input name="prenom" type = "text" value = "' . $client->prenom . '" />
				<input name="dateNaiss" type = "date" value = "' . $client->dateNaiss . '" />
				<input name="adresse" type = "text" value = "' . $client->adresse . '" />
				<input name="numTel" type = "text" value = "' . $client->numTel . '" />
				<input name="mail" type = "text" value = "' . $client->mail . '" />
				<input name="montantMax" type = "text" value = "' . $client->montantMax . '" />
				<input type = "submit" name = "modifierClient" value = "Mettre a jour" />

				</form>';

		require_once("vue/gabarit.php");
	}

	function afficherGestionFinanciere($diff, $enatt)
	{
		$header = ' < form action = "main.php" method = "post" ><p > ' . $_SESSION['empl']->nomEmploye . ' < input type = "submit" name = "accueil" value = "Accueil" /><input type = "submit" name = "deco" value = "Déconnexion" /></p ></form > ';
		$contenu = '<form id = "interventions" action = "main.php" method = "post" >
        <fieldset ><legend > Interventions client : ' . $_SESSION['client']->nom . ' </legend > ';
		if (!empty($diff) || !empty($enatt)) {
			$contenu .= '<input type = "submit" name = "payerDer" value = "Payer la dernière intervetion" />';
			foreach ($enatt as $inta) {
				$contenu .= '
    
                <p >
                    <input type = "checkbox" name = "checkInter[]" value = "' . $inta->code . '" />
                    <input type = "text" value = "' . $inta->etat . '"disabled />
                    <label > ' . $inta->dateIntervention . ' ' . $inta->nomTI . ' ' . $inta->montant . ' </label >
                </p > ';
			}

			$sommedif = 0;
			foreach ($diff as $intd) {
				$sommedif += $intd->montant;
				$contenu .= '
                <p >
                    <input type = "checkbox" name = "checkInter[]" value = "' . $intd->code . '" />
                    <input type = "text" value = "' . $intd->etat . '"disabled />
                    <label > ' . $intd->dateIntervention . ' ' . $intd->nomTI . ' ' . $intd->etat . ' ' . $intd->montant . ' </label >
                </p > ';

			}
			$_SESSION['diffEnCours'] = $sommedif;
			$contenu .= ' < p>
        <input type = "submit" name = "payer" value = "Payer" />
        <input type = "submit" name = "differer" value = "Differer" />
        </p > ';
		} else {
			$contenu .= 'Il n\'y a pas d\'intervention';
		}

		$contenu .= afficherErreur('erreurMontant');

		$contenu .= '</fieldset></form>';
		require_once("vue/gabarit.php");
	}

	function afficherErreur($n)
	{
		$erreur = '';
		if (isset($_SESSION[$n]) && !empty($_SESSION[$n])) {
			$erreur = '<p> ' . $_SESSION[$n] . '</p>';
			unset($_SESSION[$n]);
		}
		return $erreur;
	}