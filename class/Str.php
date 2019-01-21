<?php

class Str{

    // Permet de génèrer un token (une chaine de caractères est sa longueur vaudra $length)
    static function createToken($length){
        $alphaNum = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        // str_repeat() permet de répéter parm1= ce que l'on répète, param2 le nombre de fois que l'on répète
        // str_shuffle() permet de mélanger (donc on mélange ($alphaNum x 60) si $length vaut 60) a ce stade on a une trés longue chaine de caractères
        // substr() permet de retourner un segment de chaîne, param1 = la chaine, param2= le début de la chaine, param3 = la longueur de la chaine
        return substr(str_shuffle(str_repeat($alphaNum, $length)),0,$length); 
    }

    /**
     * Permet de hasher un password (bcrypt)
     *
     * @param [type] $password
     * @return boolean
     */
    static function hashPassword($password){
        return password_hash($password, PASSWORD_BCRYPT);
    }

}