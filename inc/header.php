<!-- HEADER DE L'ENSEMBLE DES PAGES DE L'ESPACE MEMBRES -->


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title></title>

        <!-- Style Bootstrap -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- Fontawesome en cdn -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <!-- Mon style Css-->
        <link rel="stylesheet" href="css/app.css"> 
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="navbar-brand" href="#">Mon Site</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Accueil <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Essai <span class="sr-only">(current)</span></a>
                    </li>
                    
                </ul>
                <ul class="navbar-nav">
                    <!-- Si un utilisateur est connecté alors...  if($auth->userIsConnect($db)): -->
                    <?php if(isset($_SESSION['infoUser'])): ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link bg-success">
                                <?= $_SESSION['infoUser']->username ?> est connecté !
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">Déconnexion</a> 
                        </li>
                    <!-- sinon si l'utilisateur n'est pas connecté alors... -->
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="register.php" class="nav-link">Inscription</a> 
                        </li>
                        <li class="nav-item">
                            <a href="login.php" class="nav-link">Connexion</a> 
                        </li>
                        
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <div class="container">

            <?php 
            if(isset($_SESSION)){
                var_dump($_SESSION);
            }
            ?>

            <!-- MESSAGE FLASH -->
            <!-- Si il y a un message flash dans notre session alors... -->
            <?php require_once 'inc/autoLoader.php'; // Permet de charger les classe utilisées dans ce fichier ?>
            <?php if(isset($_SESSION['flash'])):  //  if(isset($_SESSION['flash'])):  if(Session::getInstance()->hasFlashes()): ?>
                <!-- Pour chaque flash de la session on récup la clé qu'on nomme $type (danger, success...) et en valeur le message nommé $message -->
                <?php foreach($_SESSION['flash'] as $type => $message): //  foreach($_SESSION['flash'] as $type => $message):  foreach(Session::getInstance()->getFlashes() as $type => $message):?>
                    <!-- Création d'une div qui aura comme classe au final une classe bootstrap dynamique -->
                    <div class="alert alert-<?= $type; ?> mt-3">
                        <?= $message; ?>
                    </div>

                <?php endforeach; ?>

                <!-- On supprime le flash de la session (pour que son affichage disparaisse dès le rafraichissement de la page -->
                <?php unset($_SESSION['flash']); ?>

            <?php endif; ?>

            
