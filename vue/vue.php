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
        <p><input type="submit" name="gestionF" value="Gestion financière"/></p>';

		$contenu .= afficherErreur('erreurIdGF');

		$contenu .= '</fieldset></form><form id="monForm2" action="main.php" method="post">
	<fieldset>
		<legend>Synthèse</legend>
		<p>
			<label>id Client</label><input type="number" name="idclient" required/>
		</p>

		<p>
			<input type="submit" name="synthese" value="Synthèse client"/>
		</p>';
		$contenu .= afficherErreur('erreurIdSynthese');

	$contenu.='</fieldset>
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
		$contenu .= '<form action="main.php" method="post"><fieldset><legend>Ajouter client</legend>
				<p><label>Nom</label><input name="nom" type = "text" required/></p>
				<p><label>Prenom</label><input name="prenom" type = "text" required/></p>
				<p><label>Date de naissance</label><input name="dateNaiss" type = "date" required/></p>
				<p><label>Adresse</label><input name="adresse" type = "text"/></p>
				<p><label>Téléphone</label><input name="numTel" type = "text"/></p>
				<p><label>E-mail</label><input name="mail" type = "text"/></p>
				<p><label>Montant max</label><input name="montantMax" type = "text" required/></p>
				<input type = "submit" name = "ajouterClient" value = "Ajouter Client" />';
		$contenu .= afficherErreur('erreurClientExiste');
		$contenu.='</fieldset></form>';


		require_once("vue/gabarit.php");

	}

	function afficherSynthese($client,$interventions,$somme,$dispo)
	{
		$header = '<form action="main.php" method="post"><p>' . $_SESSION['empl']->nomEmploye .
			'<input type="submit" name="accueil" value="Accueil"/>
		<input type="submit" name="deco" value="Déconnexion"/></p></form>';

		$contenu = '';

		$contenu .= '<form action="main.php" method="post"><fieldset><legend>Synthèse client</legend>
				<p><label>Nom</label><input name="nom" type = "text" value = "' . $client->nom . '" /></p>
				<p><label>Prenom</label><input name="prenom" type = "text" value = "' . $client->prenom . '" /></p>
				<p><label>Date de naissance</label><input name="dateNaiss" type = "date" value = "' . $client->dateNaiss . '" /></p>
				<p><label>Adresse</label><input name="adresse" type = "text" value = "' . $client->adresse . '" /></p>
				<p><label>Téléphone</label><input name="numTel" type = "text" value = "' . $client->numTel . '" /></p>
				<p><label>E-mail</label><input name="mail" type = "text" value = "' . $client->mail . '" /></p>
				<p><label>Montant max</label><input name="montantMax" type = "text" value = "' . $client->montantMax . '" /></p>
				<input type = "submit" name = "modifierClient" value = "Mettre a jour" />

				</fieldset></form>';

		$contenu.='<p>Montant différé en cours : '.$somme.'€</p>';
		$contenu.='<p>Crédit possible restant : '.$dispo.'</p>';

		$contenu.='<fieldset><legend>Interventions réalisées</legend>';
		if(!empty($interventions)) {
			$contenu.='<table>
						<tr>
							<th>Date</th>
							<th>Type</th>
							<th>Mécanicien</th>
							<th>Etat</th>
							<th>Montant</th>
						</tr>';
			foreach ($interventions as $inter) {
				$contenu .= '<tr><td>' . $inter->dateIntervention .'</td><td>' . $inter->nomTI .  '</td><td>' . $inter->nomMeca . '</td><td>' . $inter->etat . '</td><td>' . $inter->montant . '</td></tr>';
			}
			$contenu.='</table>';
		}else $contenu.='<p>Aucune intervetion n\'a été réalisée.</p>';
		$contenu.='</fieldset>';
		require_once("vue/gabarit.php");
	}

	function afficherGestionFinanciere($diff, $enatt)
	{
		$header = '<form action = "main.php" method = "post" ><p > ' . $_SESSION['empl']->nomEmploye . ' <input type = "submit" name = "accueil" value = "Accueil" /><input type = "submit" name = "deco" value = "Déconnexion" /></p ></form > ';
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


			foreach ($diff as $intd) {
				$contenu .= '
                <p >
                    <input type = "checkbox" name = "checkInter[]" value = "' . $intd->code . '" />
                    <input type = "text" value = "' . $intd->etat . '"disabled />
                    <label > ' . $intd->dateIntervention . ' ' . $intd->nomTI . ' ' . $intd->etat . ' ' . $intd->montant . ' </label >
                </p > ';

			}
			$contenu .= ' <p>
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

function afficherAccueilDirecteur(){
    $header = '<form action = "main.php" method = "post" ><p > ' . $_SESSION['empl']->nomEmploye .
        ' <input type = "submit" name = "accueil" value = "Accueil" />
		<input type = "submit" name = "deco" value = "Déconnexion" /></p ></form > ';
    $contenu = '<form id = "interventions" action = "main.php" method = "post" >
				 <fieldset ><legend > Creation d\'un compte  </legend >
				<p><label>nomEmploye</label><input name="nomEmploye" type = "text"  required  /></p>
				<p><label>login</label><input name="login" type = "text" required /></p>
				<p><label>motDePasse</label><input name="motDePasse" type = "text"required /></p>
				<p><label>categorie</label><input type = "text" name = "categorie" required /></p>
				<p><input type = "submit" name = "creerCompte" value = "Creer un compte" /></p>';
    $contenu .= afficherErreur('erreurExiste');
    $contenu .= afficherErreur('erreurCat');
    $contenu.='</fieldset></form>';
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