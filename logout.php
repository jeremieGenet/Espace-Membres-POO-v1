<?php 
/************************************************************************************************************/
/********************************** PAGE DE DECONNEXION (et destruction cookie)*****************************/
/**********************************************************************************************************/

require 'inc/autoLoader.php'; // On inclue l'autoLoader qui s'occupe de charger nos classes

App::getAuth()->logout();
Session::getInstance()->setFlash('success', "Vous êtes maintenant déconnecté !");
App::redirect('login.php');

// Destruction du cookie (qui est créé lorsque l'utilisateur coche la case "se souvenir de moi" lors de la connexion)
setcookie('remember', NULL, -1); // parm 1 = récup du nom du cookie, param2= valeur du cookie que l'on met à NULL, param3= une date d'expiration négative (pour qu'il disparaisse)

// On supprime de la session tout ce qui concerne l'utilisateur
unset($_SESSION['infoUser']);


?>