<?php

// GESTION DE L'AUTHENTIFICATION
class Auth{

    // Attribut qui permettra de stocké des messages flash pré-fabriqués
    private $options_msg = [
        'restriction_msg' => "Vous n'avez pas le droit d'accéder à cette page, non , non, non !"
    ];

    // permettra à la construction de notre objet d'avoir la session
    private $session;


    public function __construct($session, $options = [])
    {
        // permettra à la construction de notre objet d'avoir la session
        $this->session = $session;
        // On fusionne le tableau $options_msg qui est un attribut de la classe avec le tableau $options qui est le param2 du constructeur
        $this->options_msg = array_merge($this->options_msg, $options);
    }

    /**
     * Permet la gestion de l'authenification, et l'insersion dans la bdd des informations que l'utilisateur entre dans le formulaire d'enregistrement (register.php)
     *
     * @param [type] $db (Représentera la connexion à la base de donnée)
     * @param [type] $username
     * @param [type] $mdp
     * @param [type] $email
     * @return void
     */
    public function register($db, $username, $mdp, $email){

        // Sécurisation des champs avant d'enregister dans la bdd
        $username = htmlspecialchars($username);
        $email = htmlspecialchars($email);
        $mdp = Str::hashPassword($mdp); // On crypte le mot de passe
        $token = Str::createToken(60); // appel de la méthode (voir dans Str.php) qui crée un token de 60 caractères (60 passé en paramètre)

        // On prépare une requête d'insersion dans la bdd de tous les champs
        $db->queryClass("INSERT INTO users SET username = ?, email = ?, mdp = ?, confirmation_token = ?" ,[$username, $email, $mdp, $token]) ;  
        // lastInsertId() est une méthode de la classe DataBase.php retourne le dernier id généré dans la bdd (donc celui de notre nouvel utilisateur)
        $user_id = $db->lastInsertId(); // $user_id est utile dans le lien envoyé à l'utilisateur pour la création de son compte

        // CONFIGURATION DE L'ENCODAGE DU MAIL
        $header="MIME-Version: 1.0\r\n"; // Version d'encodage du mail
        $header.='From:"PrimFX.com"<support@primfx.com>'."\n"; // coordonnées de l'expéditeur
        $header.='Content-Type:text/html; charset="uft-8"'."\n"; // Type encodage du text
        $header.='Content-Transfer-Encoding: 8bit'; // Type d'encodage en 8bit

        /*     
            ENVOIE D'UN EMAIL A L'UTILISATEUR POUR CONFIRMER LA CREATION DE SON COMPTE (ne fonctionne pas en local)
            Envoie du mail (qui envoie sur la page confirm.php avec le token qui lui a été envoyé)
        */
        // mail($_POST['email'], 'Confirmation de votre compte', "Afin de confirmer votre compte, merci de cliquer sur ce lien\n\nhttp://localhost/Espace_Membre3/confirm.php?id=". $user_id . "&token=$token", $header);

    }


    /**
     * Permet de confirmer un compte utilisateur en vérifiant si le token qu'il lui a été envoyé est le bon et ainsi mettre à jours l'enregistrement dans la bdd
     * (lors de l'enregistrement d'un nouvel utilisateur, il reçoit alors un email de confirmation de création de compte qui le dirige vers confirm.php)
     *
     * @param number $user_id
     * @param alphanum $token
     * @return boolean
     */
    public function confirm($db, $user_id, $token){

        // queryClass() permet via la classe DataBase de faire des requêtes à la bdd 
        $user = $db->queryClass('SELECT * FROM users WHERE id = ?', [$user_id])->fetch(); // On sélectionne tout (via l'id de l'utilisateur dans l'url)
        // On récup le token de l'utilisateur dont on a récup l'id dans l'url (retounera false si le token n'est pas le même que celui de la bdd)

        //die();
        // Si $user vaut true (c'est que le confirmation_token a été trouvé dans la bdd) et que le $token de l'url est le même que le confirmation_token alors...
        if($user && $user->confirmation_token == $token){
            
            // MODIFICATION DANS NOTRE BDD: on change le token pour le mettre à null, on met la date du jour au champ confirmed_at, de l'utilisateur connecté
            //$req = $pdo->prepare('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?'); 
            $db->queryClass('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?', [$user_id]); 

            // ON STOCK LES NOUVELLES INFOS UTILISATEUR DANS LA SESSION
            // On fait une nouvelle requête pour stocker dans la variable global de session les nouvelles infos sur l'utilisateur
            $user = $db->queryClass('SELECT * FROM users WHERE id = ?', [$user_id])->fetch(); // On sélectionne tout (via l'id de l'utilisateur dans l'url)

            $this->session->write('infoUser', $user);
            //$_SESSION['infoUser'] = $infoUser;
            // $_SESSION['auth'] = $tokenUser; // On stock dans la variable super global le token de l'utilisateur connecté
            //var_dump($user); // Affiche un objet qui contient : confirmation_token => 'mCPkpTehRdQHBlg8NXcw0xSqA6kldoGNxI26ZSGO0tVbeXgKDU804X1kE4VL'
            return true; // on retourne true pour dire que la confirmation est ok

        // Sinon c'est que le token n'est pas le même, ou qu'il a déjà été utilisé (on le met à null dan la requête UPDATE un peu plus haut)
        }else{
            return false;
        }
    }

    // Permet de vérifié que l'utilisateur ne soit pas connecté et redirection vers la page de connexion
    public function restrict(){
        
        // VERIFICATION DE L'ETAT DE LA SESSION COURANTE (permet de ne pas ouvrir plusieurs fois une session)
 
        // Si il n'y a pas de session infoUser (si personne n'est connecté) alors...
        if(!$this->session->read('infoUser')){ 
            // le "$this->options_msg['restriction_msg']" correspond à un message pré-défini de notre classe Auth.php (voir l'attribut $options_msg de la classe)
            $this->session->setFlash('danger', $this->options_msg['restriction_msg']); 
            header('location: login.php');
            exit(); // On stop le script !
        }
    }


    /**
     * Permet de vérifié si un utilisateur est connecté ou non
     *
     * @return false|session (retourne false ou la session en cours)
     */
    public function userIsConnected(){
        if(!$this->session->read('infoUser')){
            return false;
        }
        return $this->session->read('infoUser');
    }

    /**
     * Permet de connecté un utilisateur (dans la session)
     *
     * @param array $user
     * @return void
     */
    public function connectUser($user){
        $this->session->write('infoUser', $user);
    }
    // Permet de se reconnecté via le cookie nommé 'remember' (créé lorsque l'utilisateur coche le case chekbox 'se souvenir de moi' du formulaire de connexion login.php)


    /**
     * Permet de se connecté à la session si il existe le cookie nommé "remember"
     *
     * @param [type] $db
     * @return void
     */
    public function connectFromCookie($db){
        
        // Si il y a un cookie nommé "remember" et que l'utilisateur n'est pas connecté alors...
        if(isset($_COOKIE['remember']) && !$this->userIsConnected()){

            // var_dump($_COOKIE['remember']); // Renvoi l'id utilisateur concaténé à un double égal concaténé au remember_token

            // On sépare le cookie ou il y a les double égal
            $remember_token = $_COOKIE['remember'];
            $parts = explode('==', $remember_token); // On sépare le cookie ou il y a le double égale (donc ici séparé en deux)
            $user_id = $parts[0]; // On récup l'id utilisateur
            // On fait une requete pour récup l'utilisateur qui correspond à cet id
            $user = $db->queryClass('SELECT * FROM users WHERE id = ?', [$user_id])->fetch(); 

            // Si il y a bien un utilisateur qui correspond à la requête précédente alors...
            if($user){
                // On vérif si le token attendu correspond à celui stocké dans le cookie (pour être sûr qu'il n'a pas été modifié)
                $expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'ratonlaveurs'); // On stock dans $expected, le token attendu (avec la même méthode de création de cookie, que celle utilisé à la création de la clé du cookie de la session)
                if($expected == $remember_token){
                    $this->connectUser($user); // On connecte l'utilisateur

                    setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7); // On réinitialise le cookie
                }else{
                    // Destruction du cookie (qui est créé lorsque l'utilisateur coche la case "se souvenir de moi" lors de la connexion)
                    setcookie('remember', NULL, -1); // parm 1 = récup du nom du cookie, param2= valeur du cookie que l'on met à NULL, param3= une date d'expiration négative (pour qu'il disparaisse)
                }
            }else{
                // Destruction du cookie (qui est créé lorsque l'utilisateur coche la case "se souvenir de moi" lors de la connexion)
                setcookie('remember', NULL, -1); // parm 1 = récup du nom du cookie, param2= valeur du cookie que l'on met à NULL, param3= une date d'expiration négative (pour qu'il disparaisse)
            }
        }
    }


    /**
     * Permet de se connecter à l'espace membre (retourne la session de l'utilisateur si le pseudo et le mdp sont ok, sinon retourne false)
     *
     * @param [type] $db
     * @param [type] $username
     * @param [type] $password
     * @param boolean $remember
     * @return void
     */
    public function login($db, $username, $password, $remember = false){

        // On fait une sélection ou le pseudo ou l'email serait égal au pseudo tapé par l'utilisateur 
        // (ou utilise des clés plutôt que des '?' pour pouvoir faire une recherche sur les 2 champs du formulaire) 
        // et avec un champ 'confirmed_at qui ne soit pas null (pour s'assurer que le compte à bien été validé auparavant)
        $user = $db->queryClass('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL',
                ['username' => $username])->fetch();
        
        // On vérifie si le mot de passe posté et le même que celui dans la bdd avec la méthode password_verify() (qui returne true ou false)
        if(password_verify($password, $user->mdp)){
            $this->connectUser($user); // On connecte l'utilisateur

            // On vérif si l'utilisateur à cocher le case chekbox 'se souvenir de moi'
            if(isset($remember)){
                $this->remember($db, $user->id);
            }
            return $user; // On retourne l'utilisateur en actuel

        }else{
            return false; // Ce qui provoquera un message flash
            //$_SESSION['flash']['danger'] = 'Identifiant ou mot de passe incorrect !';
        }
    }


    /**
     * Permet de se déconnecter et de supprimer le cookie (remember)
     *
     * @return void
     */
    public function logout(){
        // Destruction du cookie (qui est créé lorsque l'utilisateur coche la case "se souvenir de moi" lors de la connexion)
        setcookie('remember', NULL, -1); // parm 1 = récup du nom du cookie, param2= valeur du cookie que l'on met à NULL, param3= une date d'expiration négative (pour qu'il disparaisse)

        // On supprime de la session tout ce qui concerne l'utilisateur
        $this->session->delete('infoUser');
    }


    public function remember($db, $user_id){
        $remember_token = Str::createToken(250);  // Crétion d'un token de 250 caractères (250 passé en paramètre)
        $db->queryClass('UPDATE users SET remember_token = ? WHERE id = ?', [$remember_token, $user_id]);

        /***************************************************************/
        /****************** CREATION D'UN COOKIE **********************/
        /*************************************************************/
        // setcookie() parm1= nom du cookie,
        // param2= valeur du cookie (attention, la valeur est stockée sur le navigateur client), ici l'id utilisateur == le remember_token concaténé à un hash de l'id et du mot ratonlaveurs défini aléatoirement,
        // param3= le temps après lequel le cookie expire, time() qui renvoie le timestamp auquel on ajoute une opération qui réprésente
        // 60min * 60sec * 24h * 7jours pour convertir le timestamp qui est milisecondes en jours, soit un cookie qui aura une durée de vie de 7 jours
        setcookie('remember', $user_id . '==' . $remember_token . sha1($user_id) . 'ratonlaveurs', time() + (60 * 60 * 24 * 7));
    }


    /**
     * Permet de modifié son mot de passe (avec envoie de email)
     *
     * @param [type] $db
     * @param [type] $email
     * @return void
     */
    public function resetPassword($db, $email){
        
        // on fait une requête en selectionnant via l'email et en s'assurant que l'utilisateur à bien validé la création de son compte (si confirmated_at n'est pas null alors c'est le cas)
        $user = $db->queryClass('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL', [$_POST['email']])->fetch();

        // Si il y a un utilisateur qui correspond à la req ci-dessus alors...
        if($user){

            // On génère un nouveau token 
            $reset_token = Str::createToken(60);
    
            //  On fait une requête pour insérer dans la bdd un reset_token et sa date 
            $user = $db->queryClass('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?', [$reset_token, $user->id]);

            // Message de succès et redirection
            $_SESSION['flash']['success'] = 'Les instructions pour récupérer votre mot de passe vous ont été envoyées sur votre adresse email!';
            header('location: login.php'); // On dirige vers la page du compte utilisateur

            // CONFIGURATION DE L'ENCODAGE DU MAIL DE RECUPERATION
            $header="MIME-Version: 1.0\r\n"; // Version d'encodage du mail
            $header.='From:"PrimFX.com"<support@primfx.com>'."\n"; // coordonnées de l'expéditeur
            $header.='Content-Type:text/html; charset="uft-8"'."\n"; // Type encodage du text
            $header.='Content-Transfer-Encoding: 8bit'; // Type d'encodage en 8bit
            /*     
                ENVOIE D'UN EMAIL A L'UTILISATEUR POUR LA RECUPERATION DE SON MOT DE PASSE (ne fonctionne pas en local)
                Envoie du mail (qui envoie sur la page reset.php avec le nouveau token qui lui a été envoyé)
            */

            // mail($_POST['email'], 'Réinitialisation de votre mot de passe', "Afin de réinitialiser votre mot de passe, merci de cliquer sur ce lien\n\nhttp://localhost/Espace_Membre3/reset.php?id={$user->id}&token=$reset_token", $header);

            return $user; // On retourne l'utilisateur

        }else{
            return false; 
        }

    }


    /**
     * Permet
     *
     * @param [type] $db
     * @param [type] $user_id
     * @param [type] $token
     * @return void
     */
    public function checkResetToken($db, $user_id, $token){

        // Explication: on récup l'id et le token de l'url, et la date de reset du mot de passe (reset_at) mais il faut que cette date soit supérieur 
        // à la date du jour à laquel on retranche 30 minutes (date_sub() permet de soustraire une durée à un objet DateTime, soit on soustrait 30 min à NOW() qui est aujourd'hui)
        $user = $db->queryClass('SELECT * FROM users WHERE id = ? AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)', [$user_id, $token])->fetch();

        return $user;
    }


}