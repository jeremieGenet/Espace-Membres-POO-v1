<?php
// CREATION D'UN AUTOLOADER QUI VA S'OCCUPER DE CHARGER NOS CLASSES
spl_autoload_register('app_autoload');

function app_autoload($class){
    require "class/$class.php";
}