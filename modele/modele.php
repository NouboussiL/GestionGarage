<?php
require_once('modele/connect.php');


function getConnect()
{
    $connexion = new PDO('mysql:host=' . SERVEUR . ';dbname=' . BDD, USER, PASSWORD);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connexion->query('SET NAMES UTF8');
    return $connexion;

}


function getEmploye($login, $motdepasse)
{
    $connexion = getConnect();
    //' union select * from employe where login ='ar
    $requete = "select *  from employe where login='$login' and motDePasse='$motdepasse'";
    $resultat = $connexion->query($requete);
    $resultat->setFetchMode(5);
    $employe = $resultat->fetch();
    $resultat->closeCursor();
	return $employe;

}

function getClient($id){
    $connexion = getConnect();
    $requete = "select *  from client where idClient=$id";
    $resultat = $connexion->query($requete);
    $resultat->setFetchMode(5);
    $client = $resultat->fetch();
    $resultat->closeCursor();
    return $client;
}

function getInterDiff($id){
    $connexion = getConnect();
    $requete = "select *  from intervention NATURAL JOIN typeintervention where idClient=$id and etat='differe' order by dateIntervention desc";
    $resultat = $connexion->query($requete);
    $resultat->setFetchMode(5);
    $diff = $resultat->fetchAll();
    $resultat->closeCursor();
    return $diff;
}

function getInterEnAttente($id){
    $connexion = getConnect();
    $requete = "select *  from intervention NATURAL JOIN typeintervention where idClient=$id and etat='en attente de payement' order by dateIntervention desc";
    $resultat = $connexion->query($requete);
    $resultat->setFetchMode(5);
    $enatt = $resultat->fetchAll();
    $resultat->closeCursor();
    return $enatt;
}

function payerInter($code){
    $connexion = getConnect();
    $requete = " update intervention set etat='paye' where code=$code";
    $resultat = $connexion->query($requete);
    $resultat->closeCursor();
}

function getEnAttente($inter){
    $connexion = getConnect();
    $requete = "select *  from intervention NATURAL JOIN typeintervention where code=$inter and etat='en attente de payement'";
    $resultat = $connexion->query($requete);
    $resultat->setFetchMode(5);
    $enatt = $resultat->fetch();
    $resultat->closeCursor();
    return $enatt;
}

function differer($inter){
        $connexion = getConnect();
        $requete = " update intervention set etat='differe' where code=$inter";
        $resultat = $connexion->query($requete);
        $resultat->closeCursor();
}

function getIdClient($nom,$date){
    $connexion = getConnect();
    $requete = "select *  from client where dateNaiss='$date' and nom='$nom'";
    $resultat = $connexion->query($requete);
    $resultat->setFetchMode(5);
    $client = $resultat->fetch();
    $resultat->closeCursor();
    return $client;
}
