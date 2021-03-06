<?php

namespace Controller;

use Phunder\Core\Controller\Controller as ControlleurFramework;
use Phunder\Core\Messager;
use Phunder\Core\User\UserManager;
use Article\Article;
use User\User;
use Categorie\Categorie;


class Admin extends ControlleurFramework
{

    public static function mandatoryData() : void
    {
        self::set('messages', Messager::output());
    }// fin mandatorydata()

    // Méthodes vérification droits d'accès de l'utilisateur courant
    // Void fait en sorte de ne rien retourner
    public static function verifAdmin () : void{
        $manager = new UserManager();

        if(!$manager->isLoggedIn() || $manager->get('type') !== 'admin'){
            header("Location: ../");
        }

    }
//////////////////////////////////////////////////////////// ARTICLES METHODES /////////////////////////////////////////////////////////////////////////////////////////////////
    // Accueil - liste des articles
    public static function listeArticles() : void
    {
        self::verifAdmin();
        self::set('titre_HTML', 'liste articles');
        self::set('liste', Article::liste());
        self::render('admin/liste_articles.html');
    }

    // Ajouter article - ajout des articles
    public static function ajouterArticle() : void
    {
        self::verifAdmin();
        self::set('nom_cat', Categorie::liste());

        // formulaire ajout article
        if(isset($_POST['ajouter']) && User::verifToken($_POST['token'] ?? '')){
            $ajout = Article::ajouter($_POST['titre'], $_POST['contenu'], $_POST['id_cat']);

            //si ajouté, vider le formulaire
            if($ajout){
                unset($_POST);
            }
        }

        self::set('titre', $_POST['titre'] ?? ''); // soit défini soit et non nul soit chaine vide
        self::set('contenu', $_POST['contenu'] ?? ''); // soit défini soit et non nul soit chaine vide

        $manager = new UserManager();
        self::set('token', $manager->get('token'));

        self::set('titre_HTML', 'Ajouter un article');
        self::render('admin/ajouter_article.html');
    }

    //function modifier article

    public static function modifierArticle(int $id_article){
        self::verifAdmin(); // verif admin

         // on récupère les elements du formulaires pour future modification
        try{
            $article = new Article($id_article);

            //modification si modif actionb et token vérifié
            if(isset($_POST['modifier']) && User::verifToken($_POST['token'] ?? '')) {
                $article->modifier($_POST['titre'], $_POST['contenu']);
            }

            //variable de template à récupéré
            self::set('article',[
                'id' => $article ->getId(),
                'titre' => $article->getTitre(),
                'contenu' => $article->getContenu()
            ]);

            $manager = new UserManager();
            self::set('token', $manager->get('token'));
            self::set('titre_HTML', 'Modifier un article');

            self::render('admin/modifier_article.html');
        }
        catch (\Exception $e){
            Messager::message(Messager::MSG_WARNING, $e->getMessage());
            self::set('titre_HTML', 'Erreur');
            self::render('admin/erreur.html');

        }
    }

    public static function supprimerArticle(int $id_article){
        self::verifAdmin();

        try{
            $article = new Article($id_article);
            $article->supprimer();
            header("Location: dashboard");
        }
        // le block ctach ne s'execute que si une exception est lancée
        catch (\Exception $e){
            Messager::message(Messager::MSG_WARNING, $e->getMessage());
            self::set('titre_HTML', 'Erreur');
            self::render('admin/erreur.html');
        }


    }


    //////////////////////////////////////////////////////////// CATEGORIES METHODES /////////////////////////////////////////////////////////////////////////////////////////////////



    public static function listeCategories() : void
    {
        self::verifAdmin();
        self::set('titre_HTML', 'liste catégories');
        self::set('liste', Categorie::liste());
        self::render('admin/liste_categories.html');
    }

    // // Ajouter catégorie - ajout des catégories
    public static function ajouterCategories() : void
    {
        self::verifAdmin();

        // formulaire ajout article
        if(isset($_POST['ajouter']) && User::verifToken($_POST['token'] ?? '')){
            $ajout = Categorie::ajouter($_POST['nom_cat'], $_POST['description_cat']);

            //si ajouté, vider le formulaire
            if($ajout){
                unset($_POST);
            }
        }

        self::set('nom_cat', $_POST['nom_cat'] ?? ''); // soit défini soit et non nul soit chaine vide
        self::set('description_cat', $_POST['description_cat'] ?? ''); // soit défini soit et non nul soit chaine vide

        $manager = new UserManager();
        self::set('token', $manager->get('token'));

        self::set('titre_HTML', 'Ajouter une catégorie');
        self::render('admin/ajouter_categorie.html');
    }

    // //function modifier article

    public static function modifierCategorie(int $id_cat){
        self::verifAdmin(); // verif admin

         // on récupère les elements du formulaires pour future modification
        try{
            $categorie = new Categorie($id_cat);

            //modification si modif actionb et token vérifié
            if(isset($_POST['modifier']) && User::verifToken($_POST['token'] ?? '')) {
                $categorie->modifier($_POST['nom_cat'], $_POST['description_cat']);
            }

            //variable de template à récupéré
            self::set('categorie',[
                'id_cat' => $categorie ->getId(),
                'nom_cat' => $categorie->getNom(),
                'description_cat' => $categorie->getDescription()
            ]);

            $manager = new UserManager();
            self::set('token', $manager->get('token'));
            self::set('titre_HTML', 'Modifier une catégorie');

            self::render('admin/modifier_categorie.html');
        }
        catch (\Exception $e){
            Messager::message(Messager::MSG_WARNING, $e->getMessage());
            self::set('titre_HTML', 'Erreur');
            self::render('admin/erreur.html');

        }
    }

    public static function supprimerCategorie(int $id_cat){
        self::verifAdmin();

        try{
            $categorie = new Categorie($id_cat);
            $categorie->supprimer();
            header("Location: categories");
        }
        // le block ctach ne s'execute que si une exception est lancée
        catch (\Exception $e){
            Messager::message(Messager::MSG_WARNING, $e->getMessage());
            self::set('titre_HTML', 'Erreur');
            self::render('admin/erreur.html');
        }


    }

}// fin class admin
