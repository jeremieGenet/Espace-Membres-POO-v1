<?php

/****************************************************************************************************************/
/********* PAGE D'ENREGISTREMENT D'UN NOUVEAU MEMBRE (avec envoie de mail pour confirmation de compte) *********/
/**************************************************************************************************************/

require_once 'inc/autoLoader.php'; // Permet de charger les classe utilisées dans ce fichier

$db = App::getDataBase(); // Connexion à la bdd (getDataBase est une méthode statique)


// S'il n'y a pas d'utilisateur connecté alors...
if(!isset($_SESSION['infoUser'])){

    // VERIFICATIONS LORS DE LA SOUMISSION DU FORMULAIRE
    if(!empty($_POST)){
        $errors = []; // Tableau qui contiendra les différentes erreurs

        // VERIFICATION DU CHAMP USERNAME
        $validator = new Validator($_POST);
        $validator->isFormat('username', "Votre mot de passe ne peut contenir que des lettres, des chiffres ou des signes de type underscore");
        // Si il n'y a pas d'erreur (isValid() verifie si le tableau des erreur est vide) alors...
        if($validator->isValid()){
            $validator->isUnique('username', $db, 'users', "Ce pseudo est déjà pris !");
        }

        // VERIFICATION DU CHAMP EMAIL
        $validator->isEmail('email', "Votre adresse email n'est pas valide !");
        // Si il n'y a pas d'erreur (isValid() verifie si le tableau des erreur est vide) alors...
        if($validator->isValid()){
            $validator->isUnique('email', $db, 'users', "Cette adresse email est déjà utilisée pour un autre compte !");
        }
        
        // VERIFICATION DU MOT DE PASSE
        $validator->isConfirmed('mdp', "Vous n'avez pas entrer de mot de passe !", "Votre mot de passe doit être le même que votre confirmation de mot de passe !");

        // INSERTION DANS LA BDD DU FORMULAIRE
        // Si il n'y a pas d'erreur (isValid() verifie si le tableau des erreur est vide) alors...
        if($validator->isValid()){

            // On crée un objet d'authentification (via la class App.php qui initialise un objet de la classe Auth.php),
            // puis avec la méthode register(), on sécurise les champs du formulaire, Insertion dans la bdd, envoie d'un email de confirmation de création de compte
            App::getAuth()->register($db, $_POST['username'], $_POST['mdp'], $_POST['email']); // en param le contenu des champs du formulaire

            // On crée une instance de session (soit un session_start()) dans laquelle on stock un message flash
            Session::getInstance()->setFlash('success', 'Un email de confirmation vous a été envoyé pour valider votre compte !');

            App::redirect('login.php'); // On redirige vers la page de connexion et on arrête le script (exit())
            
        }else{
            // On stock les erreurs dans le tableau $errors (déclaré plus haut)
            $errors = $validator->getErrors();
        }

    }
// Sinon qqun est connecté et alors...
}else{
    $_SESSION['flash']['warning'] = 'Vous êtes déjà connecté !';
    header('location: account.php'); // On redirige vers le compte utilisateur
}

?>

<?php require 'inc/header.php'; ?>

<h1>S'inscrire</h1>

<!-- AFFICHAGE DES ERREURS DU FORMULAIRE -->
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
        <label for="">Pseudo</label>
        <input type="text" name="username" class="form-control">
    </div>
    <div class="form-group">
        <label for="">Email</label>
        <input type="email" name="email" class="form-control">
    </div>
    <div class="form-group">
        <label for="">Mot de passe</label>
        <input type="password" name="mdp" class="form-control">
    </div>
    <div class="form-group">
        <label for="">Confirmation de mot de passe</label>
        <input type="password" name="mdp2" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">S'inscrire</button>

</form>


<?php require 'inc/footer.php'; ?>

