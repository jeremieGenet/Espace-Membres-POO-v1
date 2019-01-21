<?php

// Permet de gérer la connexion à la session les messages flashes, l'enregistrement de nouvelle info dans la session...
class Session{

    static $instance = null; // Permettra de sauvegarder l'instance de la session qui est déjà chargée (null par défaut)


    // Attention si une classe possède un constructeur sans paramètre (comme ici), alors la classe ne peut être instanciée qu'un fois (sinon comment différencier les différentes instance?)
    // Et justement, il est important que cette classe se soit pas instancié plusieurs fois dans un même fichier (un session_start() ne peut être présent plusieurs fois, sous peine d'erreur)
    public function __construct()
    {
        session_start();
    }


    /**
     * Crée une nouvelle session et s'assure qu'aucune autre instance de session n'existe déjà (sinon ça provoquerait une erreur)
     *
     * @return $instance
     */
    static function getInstance(){
        // VERIFICATION SI IL Y A DEJA UNE INSTANCE EN COURS AVEC CETTE CONDITION :
        // Si il n'y a pas d'instance (si $instance est null) alors...
        if(!self::$instance){
            self::$instance = new Session(); // On inclu et charge la session (on sauvegarde dans l'attribut $instance l'instance 'new Session')
        }
        // Et on retourne l'instance stockée
        return self::$instance;
        
    }

    /**
     * Permet de créé un message flash qui sera stocké dans la session globlal ($_SESSION)
     *
     * @param [type] $key
     * @param [type] $message
     * @return void
     */
    public function setFlash($key, $message){
        $_SESSION['flash'][$key] = $message;
    }


    /**
     * Permet de déterminer si il y a ou non des messages flash (true s'il y en a, false si il n'y en a pas)
     *
     * @return boolean
     */
    public function hasFlashes(){
        if(isset($_SESSION['flash'])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Permet de renvoyer tous les messages flash (et sa suppression du flash de la session, après son affichage)
     *
     * @return string $flash
     */
    public function getFlashes(){
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']); // on supprime le flash de la session (pour que son affichage disparaisse dès rafraichissement de la page)
        return $flash;
    }

    /**
     * Permet d'écrire des informations dans la session (sous forme de tableau associatif clé => valeur)
     *
     * @param string $key
     * @param var $value
     * @return void
     */
    public function write($key, $value){
        $_SESSION[$key] = $value;
    }


    /**
     * Permet de lire les informations de la session
     *
     * @param string $key
     * @return session|null
     */
    public function read($key){
        // Si il y a bien une session alors...
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }else{
            return null;
        }
        
    }

    /**
     * Permet de supprimer les informations de la session
     *
     * @param [type] $key
     * @return void
     */
    public function delete($key){
        // On supprime de la session
        unset($_SESSION[$key]);
    }

}