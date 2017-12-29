<?php
	require_once("vue/vue.php");
	require_once("modele/modele.php");
	function ctlAcceuil()
	{
		afficherAccueil();
	}

	class ExceptionMontatnDepasse extends Exception
	{

	}

	class ExceptionLogin extends Exception
	{

	}

	class ExceptionIdNonTrouve extends Exception
	{

	}

	class ExceptionClientNonTrouve extends Exception
	{
	}

	function ctlPayerDerniere()
	{
		$int = getInterEnAttente($_SESSION['client']->idClient);
		if (!empty($int)) {
			payerInter($int[0]->code);
		}
	}

	function ctlPayerInter($checkInter)
	{
		if (!empty($checkInter)) {
			foreach ($checkInter as $inter) {
				payerInter($inter);
			}
		}
	}

	function ctlDiffererInter($checkInter)
	{
		$montant = 0;
		$codes = [];
		foreach ($checkInter as $inter) {
			//parcours de tous les checkbox et recuperer ceux en attente
			if (!empty($interEnAtt = ctlGetEnAttente($inter))) {
				$montant = $montant + $interEnAtt->montant;
				$codes[] = $inter;
			}
		}
		if ($_SESSION['client']->montantMax - ($_SESSION['diffEnCours'] + $montant) >= 0) {
			foreach ($codes as $inter) {
				differer($inter);
			}
		} else {
			throw new ExceptionMontatnDepasse("Montant autorisé est depassé");
		}
	}

	function ctlGetEnAttente($inter)
	{
		return getEnAttente($inter);
	}

	function ctlAfficherPageCorrespondante($login, $motdepasse)
	{
		$employe = ctlChercherIdentifiantsEmploye($login, $motdepasse);
		$_SESSION['empl'] = $employe;
		switch ($employe->categorie) {
			case'agent':

				afficherAccueilAgent($employe);
				break;
			case'mecanicien':
				break;
			case'directeur':
				break;
		}
	}

	function ctlChercherIdentifiantsEmploye($login, $motdepasse)
	{
		if ($employe = getEmploye($login, $motdepasse)) {
			return $employe;
		} else {
			throw  new ExceptionLogin("Login ou mot de passe incorrect");
		}
	}

	function ctlGestionFinanciere($id)
	{
		if ($client = ctlGetClient($id)) {
			$_SESSION['client'] = $client;
			$diff = getInterDiff($id);
			$enatt = getInterEnAttente($id);
			afficherGestionFinanciere($diff, $enatt);
		} else {
			throw new ExceptionIdNonTrouve("Id non trouvé");
		}
	}

	function ctlMettreAJourClient()
	{
		$infos=array("nom","prenom","dateNaiss","adresse","numTel","mail","montantMax");
		foreach ($infos as $i){
			if(!empty($_POST[$i])){

			}
		}

		foreach($_POST as $key=>$val){
			if($val != $_SESSION[$key]){

			}
		}
	}

	function ctlSyntheseClient($id)
	{
		$client=ctlGetClient($id);
		$_SESSION['client']=$client;
		afficherSynthese($client);
	}

	function ctlGetClient($id)
	{
		$client = getClient($id);
		return $client ? $client : false;
	}

	function ctlGetIdClient($nom, $dateNaiss)
	{
		if ($client = getIdClient($nom, $dateNaiss)) {
			$_SESSION['rechercheIdClient'] = $client;
		} else {
			throw new ExceptionClientNonTrouve("Aucun client " . $nom . " né le " . $dateNaiss . " trouvé.");
		}

	}

	function CtlErreur($erreur)
	{
		afficherErreurLogin($erreur);
	}