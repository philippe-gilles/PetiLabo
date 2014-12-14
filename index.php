<?php
/* 
    Page PHP PetiLabo 2.0.beta
*/

require_once "petilabo/path.php";
inclure_site("moteur_site");
$moteur = new moteur_site();

$moteur->ouvrir_entete();
$moteur->ecrire_entete();
$moteur->fermer_entete();

$moteur->ouvrir_corps();
$moteur->ecrire_corps();
$moteur->fermer_corps();