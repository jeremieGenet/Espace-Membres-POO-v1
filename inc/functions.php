<?php
/* FICHIER QUI CONTIENDRA LES FONCTIONS UTILES A L'ESPACE MEMBRES */

// Permet de vérifié que l'utilisateur ne soit pas connecté et redirection vers la page de connexion
function logged_user(){
    // VERIFICATION DE L'ETAT DE LA SESSION COURANTE (permet de ne pas ouvrir plusieurs fois une session)
    // si le statut de la session n'existe pas alors...
    if(session_status() == PHP_SESSION_NONE){
        session_start(); // On démarre une session
    }
    // Si il n'y a pas de session userInfo (si personne n'est connecté) alors...
    if(!isset($_SESSION['infoUser'])){ 
        $_SESSION['flash']['danger'] = "Vous n'avez pas le droit d'accéder à cette page, non , non, non !";
        //header('location: login.php');
        exit(); // On stop le script !
    }
}

// Permet de se reconnecté via le cookie nommé 'remember' (créé lorsque l'utilisateur coche le case chekbox 'se souvenir de moi' du formulaire de connexion login.php)
function reconnect_from_cookie(){

    // VERIFICATION DE L'ETAT DE LA SESSION COURANTE (permet de ne pas ouvrir plusieurs fois une session)
    // si le statut de la session n'existe pas alors...
    if(session_status() == PHP_SESSION_NONE){
        session_start(); // On démarre une session
    }

    // Si il y a un cookie nommé "remember" dans la session alors...
    if(isset($_COOKIE['remember']) && !isset($_SESSION['infoUser'])){
        require_once 'inc/db.php';

        // Si $pdo n'est pas accessible, alors... on passe $pdo en global
        if(!isset($pdo)){
            global $pdo; // On passe $pdo en global (pour pouvoir récup $pdo de partout)
        }
        
        // var_dump($_COOKIE['remember']); // Renvoi l'id utilisateur concaténé à un double égal concaténé au remember_token

        // On sépare le cookie ou il y a les double égal
        $remember_token = $_COOKIE['remember'];
        $parts = explode('==', $remember_token); // On sépare le cookie ou il y a le double égale (donc ici séparé en deux)
        $user_id = $parts[0]; // On récup l'id utilisateur
        // On fait une requete pour récup l'utilisateur qui correspond à cet id
        $req = $pdo->prepare('SELECT * FROM users WHERE id = ?'); // 
        $req->execute([$user_id]);
        $user = $req->fetch();

        var_dump($user);
        die();

        // Si il y a bien un utilisateur qui correspond à la requête précédente alors...
        if($user){
            // On vérif si le token attendu correspond à celui stocké dans le cookie (pour être sûr qu'il n'a pas été modifié)
            $expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'ratonlaveurs'); // On stock dans $expected, le token attendu (avec la même méthode de création de cookie, que celle utilisé à la création de la clé du cookie de la session)
            if($expected == $remember_token){
                session_start();
                $_SESSION['infoUser'] = $user; // On connecte l'utilisateur
                setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7); // On réinitialise le cookie
            }else{
                // Destruction du cookie (qui est créé lorsque l'utilisateur coche la case "se souvenir de moi" lors de la connexion)
                setcookie('remember', NULL, -1); // parm 1 = récup du nom du cookie, param2= valeur du cookie que l'on met à NULL, param3= une date d'expiration négative (pour qu'il disparaisse)
            }
        }else{
            // Destruction du cookie (qui est créé lorsque l'utilisateur coche la case "se souvenir de moi" lors de la connexion)
            setcookie('remember', NULL, -1); // parm 1 = récup du nom du cookie, param2= valeur du cookie que l'on met à NULL, param3= une date d'expiration négative (pour qu'il disparaisse)
        }
    }
}

