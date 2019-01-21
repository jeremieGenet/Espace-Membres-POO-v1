<?php 

/******************************************************************************************************/
/*************** PAGE QUI PERMET DE REINITIALISER SON MOT DE PASSE ***********************************/
/****************************************************************************************************/

// Pour tester il faut une url de type : http://localhost/Espace_Membre3/reset.php?id=44&token=xBwFaby9UBj8oM5sjjjBEaWedgTV6HcLALY6OObFiA6hKO7yTPVdbavwbknr
// avec l'id et le token que l'utilisateur reçoit par email

require_once 'inc/autoLoader.php'; // Permet de charger les classe utilisées dans ce fichier

// Si il y a une variable id et token dans l'url alors...
if(isset($_GET['id']) && isset($_GET['token'])){

    $auth = App::getAuth();
    $db = App::getDatabase();

    $user = $auth->checkResetToken($db, $_GET['id'], $_GET['token']);

    // Si la requete à bien fonctionnée (c'est qu'il y a l'id et le reset_token dans l'url et donc que l'utilisateur à cliqué sur le lien qu'il a reçu par email) alors...
    if($user){
        // TRAITEMENT DU FORMULAIRE DE NOUVEAU MOT DE PASSE 
        if(!empty($_POST)){

            // Vérification du mot de passe
            $validator = new Validator($_POST);
            $validator->isConfirmed('mdp');

            // Si il n'y a pas d'erreur (isValid() verifie si le tableau des erreur est vide) alors...
            if(!$validator->isValid()){

                // On hash le mot de passe (avec notre fonction qui se trouve dans la classe Str.php)
                $mdp = Str::hashPassword($_POST['mdp']);
                // On insère le nouveau mot de passe et on met à null les reset_token et reset_at (qui n'ont plus d'utilités)
                $db->queryClass('UPDATE users SET mdp = ?, reset_at = NULL, reset_token = NULL WHERE id = ?', [$mdp, $_GET['id']]);

                Session::getInstance()->setFlash('success', "Votre mot de passe a bien été modifié !");
                $auth->connectUser($user); // On connect l'utilisateur
                App::redirect('account.php');
            } 
        }
    }else{
        Session::getInstance()->setFlash('danger', "Ce token n'est pas valide !");
        App::redirect('login.php');
    }

}else{
    App::redirect('login.php');
}

?>

<?php require 'inc/header.php'; ?>

<h1>Réinitialiser mon mot de passe</h1>

<!-- GESTION DES ERREUR DU FORMULAIRE -->
<?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        <p>Vous n'avez pas rempli le formulaire correctement</p>
        <ul>
            <?php foreach ($errors as $error):?>
                <li> <?= $error; ?> </li>   
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="" method="POST">
    
    <div class="form-group">
        <label for="">Mot de passe</label>
        <input type="password" name="mdp" class="form-control">
    </div>
    <div class="form-group">
        <label for="">Confirmation du mot de passe</label>
        <input type="password" name="mdp2" class="form-control">
    </div>
    

    <button type="submit" class="btn btn-primary">Réinitialiser le mot de passe</button>


</form>


<?php require 'inc/footer.php'; ?>  