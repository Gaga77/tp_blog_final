<?php

namespace User;

//  EN indiquant l'utilisation de App\App, on pourra y faire référence par le nom de classe seulement : App
use App\App;

// Pour indiquer des messages à l'utilisateur -> utilisation de la class Messager
use Phunder\Core\Messager;

use Phunder\Core\User\UserManager; // pour enregistrer les infos utilisateur en session

class User{

    // Connecter l'utilisateur
    /**
    * @param string $login nom d'utilisateur ou email
    * @param string $mdp mot de passe
    * @return bool  return true ou false -> true si connexion réussi
    */

    public static function connexion(string $login, string $mdp) : bool
    {
        // Chercher si login correpond à un pseudo ou un email
        $user = App::$db->prepare(
            'SELECT *
            FROM utilisateur
            WHERE
            pseudo = :login
            OR email = :login'
        );
        $user->bindParam(':login', $login); // le marqueeur login donc la valeur dans la base de donné du champs login sera remplacé par le $_POST login
        $user->execute(); // execution de la requete

        // si aucun utulisateur n'est trouvé on s'arrete
        if($user->rowCount() != 1){
            Messager::message(Messager::MSG_WARNING, 'Aucun utilisateur n\a été trouvé');
            return false;
        }

        $user = $user->fetch(\PDO::FETCH_ASSOC); // on récupère les informations qui match et on les met dans un tableau
        // si le mot de passe est incorrect, on s'arrête
        if(!password_verify($mdp,$user['mdp'])){
            Messager::message(Messager::MSG_WARNING, 'Mot de passe erroné');
            return false; // car demande de retour bool
        }
        //Pour engistrer les infos user en session on y retire d'abord le mdp
        unset($user['mdp']);
        $user['token'] = bin2hex(random_bytes(16));// creation du jeton de securité

        //Enregistrement en sessions (session start géré par le framework)
        $manager = new UserManager();
        $manager->logIn($user);// la méthode logIn enregistre en session -> regeneration de l'identifiant de session
        return true;  // car demande de retour bool
    } // fin function connexion

    public static function verifToken(string $jeton_formulaire) : bool {

        $jeton_session = (new UserManager())->get('token'); // on créer l'objet $jeton_formulaire directement entre parenthese pour lui appliquer la méthode get
        $verification = $jeton_formulaire === $jeton_session; // retourne la valeur de la comparaison

        if(!$verification) { // si $verification vaut false -> message d'erreur
            Messager::message(Messager::MSG_WARNING, 'Votre jeton de sécurité est invalide');
        }
        return $verification;
    }






}// fin class user
