<?php

/**********************************************************************************************************************************************/
/****** PAGE DE TRAITEMENT DE LA CONFIRMATION DE CREATION DE COMPTE DE L'UTILISATEUR (mail reçu lors de la création de son compte) ***********/
/********************************************************************************************************************************************/
/*
                
    (l'utilisateur doit avoir reçu un mail dans lequel un lien avec son id et son token renvoi ici à confirm.php, et ce lien comporte donc l'id et le token de l'utilisateur

    OBJECTIF DU TRAITEMENT: récup l'id, et le token de l'utilisateur (qui veut confirmer la création de son compte),
        et comparer le token à celui qui est dans la bdd, si c'est le cas on connecte l'utilisateur et on le diriger vers son compte.
 
        POUR TESTER ENTRER DANS L'url : http://localhost/Espace_Membre3/confirm.php?id=13&token=P2mZuDDkcYnoBG6bvABmg1xE95vEmg5jYCJMSSgHk3H6lMW5nXAcZSlqaCdy
        (id=13 pour l'utilisateur nommé 'tingle' et comme confirmation_token: P2mZuDDkcYnoBG6bvABmg1xE95vEmg5jYCJMSSgHk3H6lMW5nXAcZSlqaCdy )

        http://localhost/Espace_Membre3/confirm.php?id=44&token=1HsfEbpUorbKOoEBRr2UMX0mDpIdSpJU9Orqnk5dFtysUmiMoYX9379ldG83

*/

require 'inc/autoLoader.php'; // On inclue l'autoLoader qui s'occupe de charger nos classes


$db = App::getDataBase(); // On récup la bdd


// App::getAuth() permet de  créer un objet d'authentification (via la class App.php qui initialise un objet de la classe Auth.php)
// Si App::getAuth()->confirm() vaut true (c'est que le confirmation_token a été trouvé dans la bdd) et que le $token de l'url est le même que le confirmation_token alors...
if(App::getAuth()->confirm($db, $_GET['id'], $_GET['token'], Session::getInstance())){ // Le 4e param est une instance le session (parce que la méthode confirm() a besoin d'envoyer des infos dans la session)
    //$_SESSION['flash']['success'] = "Votre compte a bien été validé !";
    Session::getInstance()->setFlash('success', "Votre compte a bien été validé !");
    App::redirect('account.php'); // Redirection vers le fichier account.php
// Sinon c'est que le token n'est pas le même, ou qu'il a déjà été utilisé (on le met à null dan la requête un peu plus haut)
}else{
    //$_SESSION['flash']['danger'] = "Ce token n'est plus valide !"; // On stock un message d'erreur directement dans la session (on lui donne la classe bootstrap pour la couleur)
    Session::getInstance()->setFlash('danger', "Ce token n'est plus valide !");
    App::redirect('login.php'); // Redirection vers le fichier login.php
}


