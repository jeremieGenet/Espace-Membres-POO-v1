<?php
// Connexion à la base de données.
$pdo = new PDO('mysql:dbname=member_area;host=localhost', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // (CREATION D'UN ATTRIBUT)Lorsqu'il y aura une erreur, renvoie d'une exeption (PDO ne renvoie rien par défaut)
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); // (CREATION D'UN ATTRIBUT) qui permet de récupérer les erreurs dans un objet,
                                                                   // (PDO met les erreurs dans un tableau assiociatif par défaut).