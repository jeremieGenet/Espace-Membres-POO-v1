<?php



// Classe qui se charge uniquement d'initialiser les autres classe (tous les objets 'chiants' de notre Espace Membre)
class App{
    
    static $db = null;

    /**
     * Permet d'initialiser la bdd
     *
     * @return object $db
     */
    static function getDataBase(){

        // Le but de cette condition est d'initialiser la connection que si elle ne l'est pas (pour gagner en performance)
        // Si $db est différent de null (ce qui est le cas au démarrage car $db est initialisé à null dans l'attribut static) alors...
        if(!self::$db){
            // Initialisation de la base de données (pour cela on instancie la classe DataBase et on met les paramètres du constructeur de cette classe ($login, $password et $database_name))
            self::$db = new DataBase('root', '', 'member_area'); 
        }

        return self::$db;
    }

    /**
     * Permet de rediriger vers un fichier
     *
     * @param string $page (qui sera le fichier vers lequel on est redirigé)
     * @return void
     */
    static function redirect($page){
        header("location: $page"); // On redirige vers le formulaire de connexion
        exit(); // On arrete le script
    }

    /**
     * Permet de retourner et d'initialiser une nouvelle instance de la classe Auth.php (peut permettre d'inistialiser ici d'autres messages flash) 
     *
     * @return object $auth
     */
    static function getAuth(){
        // On instancie la class Auth.php (qui permet la gestion de l'authentification) avec en param1 le session actuelle, et param2 un message flash personnalisé
        return new Auth(Session::getInstance(), ['restriction_msg' => "Lol... tu es bloqué... parce que tu n'es pas connecté tu ne peux aller sur la page de compte utilisateur"]);
    }

}