<?php

class Validator{

    private $data; // $data représentera les données réçues ($_POST pour un formulaire par exemple)
    private $errors = []; // représentera le tableau des erreurs (vérification des champs du formulaire)
    
    public function __construct($data)
    {
        $this->data = $data;
    }

    // Permet de vérifier si le le champ ($field) existe bien
    private function getField($field){
        // Si le champ ($field) n'existe pas on retourne null, sinon on retourne le champ 
        if(!isset($this->data[$field])){
            echo "ATTENTION : le champ n'existe pas , veuillez vérifier le nom du champ entré en paramètre !";
            return null;
        }
        return $this->data[$field];
    }

    /**
     * Permet de formater le champ en fonction d'un Regex (ici le username (pseudo) de l'utilisateur)
     *
     * @param string $field (champ username de l'utilisateur)
     * @param array $errorMsg (contiendra le message d'erreur)
     * @return void
     */
    public function isFormat($field, $errorMsg){
        // La regex autorise des lettres de a à z majuscule comme minuscule des chiffres de 0 à 9, des underscores et tout ça plusieurs fois.
        if(!preg_match('/^[a-zA-Z0-9_]+$/', $this->getField($field))){
            $this->errors[$field] = $errorMsg;
        }
    }

    /**
     * Permet de vérifié si un champ est déjà présent dans la bdd
     *
     * @param string $field
     * @param object $db
     * @param string $tableName
     * @param array $errorMsg
     * @return boolean
     */
    public function isUnique($field, $db, $tableName, $errorMsg){
        // On récup les noms dans la bdd, qui on le même nom que celui tapé dans le champ username
        $record = $db->queryClass("SELECT id FROM $tableName WHERE $field = ?", [$this->getField($field)])->fetch(); 

        // Si il y a bien un username dans la bdd qui est le même que celui taper dans le champ username alors...
        if($record){
            $this->errors[$field] = $errorMsg;
        }
    }

    /**
     * Permet si l'adresse email est bien au format email (filter_var())
     *
     * @param string $field
     * @param array $errorMsg
     * @return boolean
     */
    public function isEmail($field, $errorMsg){
        if(!filter_var($this->getField($field), FILTER_VALIDATE_EMAIL)){
            $this->errors[$field] = $errorMsg;
        }
    }

    /**
     * Permet de vérifié si le mot de passe est présent et s'il est le même que la confirmation
     *
     * @param string $field
     * @param array $errorMsg (par défaut le message d'erreur est vide)
     * @param array $errorMsg2 (par défaut le message d'erreur est vide)
     * @return void
     */
    public function isConfirmed($field, $errorMsg = "", $errorMsg2= ""){
        // Si le mot de passe est vide alors...
        if(empty($this->getField($field))){
            $this->errors[$field] = $errorMsg;
        // Si le password tapé est différent de la confirmation de password alors...
        }elseif($this->getField($field) != $this->getField($field . '2')){ // on concatène $fiel qui vaut 'mdp' à '2' pour obtenir 'mdp2' et ainsi pouvoir les comparer
            $this->errors[$field] = $errorMsg2;
        }
    }

    /**
     * Permet de valider ou non selon qu'il y a des erreurs ou non, dans le tableau $errors
     *
     * @return boolean
     */
    public function isValid(){
        // Si le tableau $errors est vide on retourne "true" sinon "false"
        if(empty($this->errors)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Permet de retourner le tableau des erreurs
     *
     * @return array
     */
    public function getErrors(){
        return $this->errors;
    }

}