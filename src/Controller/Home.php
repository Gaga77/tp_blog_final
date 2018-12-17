<?php

// espace de nom en 1er, nom du dossier renfermant la classe (inside src)
// là ou se trouve ce fichier là là
namespace Controller;
// pas besoin d'écrire le dossier parent grace à l'autoloader et composer qui va automatiquement chercher nos class dans le dossier src

// Dans ce fichier on aura besoin d'utiliser la class controller dans phunder/core/controller
use Phunder\Core\Controller\Controller as ControlleurFramework;

// Dans ce fichier on aura besoin d'utiliser la class User danssrc/user/user
use User\User;
use Phunder\Core\Messager;
use Phunder\Core\User\UserManager;
use Article\Article;

/**
* Demo home Controller
*/

// class home hérite des méthodes et propriétés de la class controller (uniquement les propriétés et méthodes publiques et protégées (pas private))
class Home extends ControlleurFramework
{
    // Mandatory méthodes appelés automatiquement servant à initier les variables dont on aura besoin pour toutes les méthodes qui suivent en dessous.
    // actions à effectuer pour toutes les routes de ce controlleur
    // une fonction mandatoryData pour chaque controlleur
    public static function mandatoryData() : void
    {
        // tableau des messages
        self::set('messages', Messager::output());

        // Liens annexes au liens de base (admin,backoffice,deconnexion)
        // liens en fonction des log
        $manager = new UserManager();

        // si il y une connexion
        if($manager->isLoggedIn()){ // si connecté
            self::set(
                'nav_connexion',
                '<li class="nav-item"><a class="nav-link" href="#">'. $manager->get('pseudo') .'</a></li>
                <li class="nav-item"><a class="nav-link" href="logout">Déconnexion</a></li>'
            );
            // Lien vers backoffice si admin
            if($manager->get('type') === 'admin'){
                self::set(
                    'nav_admin', '<li class="nav-item"><a class="nav-link" href="admin/dashboard">Backoffice</a></li>');
                }
                else{
                    self::set('nav_admin', '');
                }
                // si utilisateur pas connecté
            } else {
                self::set('nav_connexion','<li class="nav-item"><a class="nav-link" href="login">Connexion</a></li>');
                self::set('nav_admin', '');
            }
        } // fin de mandatoryData()

        // méthode de la page d'accueil
        public static function index()
        {
            // declaration de variables à utiliser dans le template avec ::set()
            // ::set(nom_de_la_variable, valeur)
            self::set('liste', ['foo', 'bar', 'baz']);

            self::set('liste', Article::liste());

            self::set('titre_HTML', 'Blog - Accueil');
            self::set('prenom', 'Garance');

            // afficher un template
            // chemin à indiquer relatif au dossier /templates/
            self::render('home.html');
        }

        // Page de connexion
        public static function connexion()
        {
            // Traitement du formulaire de connexion
            if (isset($_POST['connexion'])){
                $connexion = User::connexion($_POST['login'], $_POST['mdp']);

                if($connexion){
                    header("Location: home"); //redirection
                }

            }

            self::set('titre_HTML', 'Blog - Connexion');
            self::render('login.html');
        }

        // L'argument $id_article est fourni via le Routeur ()
        //Il correpond au "groupe de capture" (\d+)
        // Le routeur envoit les groupes de capture à la méthide du controlleur associé
        public static function lireArticle(int $id_article){
            // essais d'instancier un objet
            try{
                $article = new Article($id_article);

                self::set('titre_HTML', $article->getTitre());
                self::set('titre', $article->getTitre());

                self::set('date', $article->dateFr());
                self::set('contenu', $article->markdownToHtml());

                self::render("article.html");

            }
            // le block ctach ne s'execute que si une exception est lancée
            catch (\Exception $e){
                Messager::message(Messager::MSG_WARNING, $e->getMessage());
                self::set('titre_HTML', 'Erreur');
                self::render('erreur.html');
            }

        }

        //page de Déconnexion - method
        public static function deconnexion() : void
        {
            // on deconnecte le user
            $manager = new UserManager();
            $manager->logOut();
            // message de succès
            Messager::message(Messager::MSG_SUCCESS, 'Vous avez bien été déconnecté');

            //On oublie pas le titre de la page
            self::set('titre_HTML', 'Blog - Déconnexion');


            // on modifie la page de connexion
            self::render('login.html');

        }


        // Page d'erreur 404
        public static function page_404()
        {
            self::render('404.html');
        }
    }
