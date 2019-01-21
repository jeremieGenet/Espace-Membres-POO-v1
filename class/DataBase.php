<?php

class DataBase{

    private $pdo;

    public function __construct($login, $password, $database_name, $host = 'localhost'){
        $this->pdo = new PDO("mysql:dbname=$database_name;host=$host", $login, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // (CREATION D'UN ATTRIBUT)Lorsqu'il y aura une erreur, renvoie d'une exeption (PDO ne renvoie rien par défaut)
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); // (CREATION D'UN ATTRIBUT) qui permet de récupérer les erreurs dans un objet,
                                                                        // (PDO met les erreurs dans un tableau assiociatif par défaut).
    }

    /**
     * Permet de faire des requêtes (préparées ou simple) à une base de donnée
     *
     * @param string $query 
     * @param boolean|array $params
     * @return PDOStatement
     */
    public function queryClass($query, $params = false){
        // Si il y a des paramètres alors on fait un requête préparée
        if($params){
            $req = $this->pdo->prepare($query); 
            $req->execute($params); 
        // Sinon on fait une requête classique (qui ne demande pas de paramètres (les "?") )
        }else{
            $req = $this->pdo->query($query);
        }
        return $req;
    }

    /**
     * Permet de retourner le dernier id inséré dans la bdd
     *
     * @return number $id
     */
    public function lastInsertId(){
        return $this->pdo->lastInsertId();
    }
}