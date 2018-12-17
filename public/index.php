<?php

// fichier de configuration obligatoire -> ce fichier contient autoload framework etc
require_once '../config/bootstrap.php';

//alias d'espace de nom -> chemin pour expliquer d'ou est issue la class Routeur et faire un raccourci vers la classs qui nous sert de routeur
use Phunder\PPRR\PPRR as Router;
use Controller\Admin as PageBack;

// Alias des controlleurs/class
// se trouvent dans : src/ + espace de nom Controllers
use Controller\Home as PageFront; // la class home sera ici PageFront

//Parametrages du Routeur
// Pour utiliser des routes simples au lieu d'expression rationnelles (regEx)
Router::setDefaultMode(Router::MODE_STRING);

// Routes -> tableau de routes

//Alias:: class -> retourne le veritable nom du'une class
new Router([
    'R>(?:/|/home)' => [PageFront::class, 'index'], // soit vide soit / soit homes
    '/login' => [PageFront::class, 'connexion'],
    '/logout' => [PageFront::class, 'deconnexion'],
    'R>/article-(\d+)' => [PageFront::class, 'lireArticle'],
    '/admin' => [
        '/dashboard' => [PageBack::class, 'listeArticles'],
        '/add_article' => [PageBack::class, 'ajouterArticle'],
        'R>/edit-(\d+)' => [PageBack::class, 'modifierArticle'], // groupe de capture ()
        'R>/supp-(\d+)' => [PageBack::class, 'supprimerArticle']
    ]
],
[PageFront::class, 'page_404']
);
