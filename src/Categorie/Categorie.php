<?php

namespace Categorie;

use Phunder\Core\Messager;
use App\App;


class Categorie {

    private $id_cat;
    private $nom_cat;
    private $date_creation;
    private $description_cat;

//     public function __construct(int $id){
//         //verifie que l'id correspond à un article, à une id existante
//         $article = App::$db->prepare('SELECT * FROM article WHERE id = :id');
//         $article->bindParam(':id', $id, \PDO::PARAM_INT);
//         $article->execute();
//
//         // si c'est pas le cas -> erreur
//         if($article->rowCount() != 1){
//             throw new \Exception("Article inconnu");
//         }
//         // définir les propriétés
//         $article = $article->fetch(\PDO::FETCH_ASSOC);
//         $this->id = $article['id'];
//         $this->titre = $article['titre'];
//         $this->date_publication = $article['date_publication'];
//         $this->contenu = $article['contenu'];
//     } // fin __construct
//
//     // pour pouvoir lire ce que renvoit les construct
//     public function getId() : int {
//         return $this->id;
//     }
//
//     public function getTitre() : string {
//         return $this->titre;
//     }
//
//     public function getDatePublication() : string {
//         return $this->date_publication;
//     }
//
//     public function getContenu() : string{
//         return $this->contenu;
//     }
//
//     public function dateFr() : string {
//         return(new \DateTime($this->date_publication))->format('d/m/Y');
//     }
//
//     public function markdownToHtml() : string {
//         $parsedown = new \Parsedown();
//         return $parsedown->text($this->contenu);
//     }
//
//
//     /**
//     * 1 - Valider  un articles
//     *
//     * @param string $titre Le titre de l'article
//     * @param string $contenu Le contenu de l'article
//     *
//     * @return bool true si données sont valides
//     */
//
//     private static function validation(string $titre, string $contenu) : bool{
//         //tableau d'erreurs possibles
//         $erreurs = [
//             'titre vide' => empty(trim($titre)),
//             'contenu vide' => empty(trim($contenu))
//         ];
//         // si erreur trouvée
//         if(in_array(true, $erreurs)){
//             Messager::message(Messager::MSG_WARNING, array_search(true, $erreurs));
//             return false;
//         }
//         return true;
//     }
//
//
//     /**
//     * 2 - Supprimer  un articles
//     */
//
//     public function supprimer()
//     {
//         $supp = App::$db->prepare('DELETE FROM article WHERE id = :id');
//         $supp->bindParam(':id', $this->id, \PDO::PARAM_INT);
//
//         $resultat = $supp->execute();
//         if(!$resultat){
//             Messager::message(Messager::MSG_WARNING, 'Impossible de supprimer l\'article');
//         }
//         Messager::message(Messager::MSG_SUCCESS, 'article supprimé');
//     }
//
//
//
//     /**
//     *  3 - Ajouter un articles
//     *
//     * @param string $titre Le titre de l'article
//     * @param string $contenu Le contenu de l'article
//     *
//     * @return bool true si inséré en BDD
//     */
//
//     public static function ajouter(string $titre, string $contenu) : bool
//     {
//         //vérification validation des données
//         if (!self::validation($titre, $contenu)){
//             return false;
//         }
//
//         //Insertion en BDD
//         $insert = App::$db->prepare(
//             'INSERT INTO article (
//                 titre,
//                 date_publication,
//                 contenu
//             ) VALUES (
//                 :titre,
//                 NOW(),
//                 :contenu
//             )'
//         );
//
//         //execution, execute() retourne un bool
//         // les index correspondent aux values de la requete insert pour dire tel marqueur va avec tel $_POST
//         $resultat = $insert->execute([
//             "titre" => $titre,
//             "contenu" => $contenu
//         ]);
//         // si l'insertion ne vaut pas true ->msg erreur
//         if(!$resultat){
//             Messager::message(Messager::MSG_WARNING, 'L\'article n\'a pas pu etre enregistré');
//             return false;
//         }
//         Messager::message(Messager::MSG_SUCCESS, 'Article ajouté');
//         return true;
//
//     } // fin fonction ajouter
//
//
//     /**
//     * 4 - Obtenir une liste d'articles
//     *
//     */
//
//     public static function liste() : array {
//         $liste = App::$db->query(
//             'SELECT
//             id,
//             titre,
//             date_publication,
//             contenu,
//             DATE_FORMAT(date_publication, "%d/%m/%Y") AS date_fr,
//             SUBSTRING(contenu, 1, 100) AS extrait
//             FROM article
//             ORDER BY
//             date_publication DESC,
//             id DESC'
//         );
//         return $liste->fetchAll(\PDO::FETCH_ASSOC);
//     }
//
//     /**
//     * 5 - Modifier article
//     * @param string $titre titre de l'article
//     * @return void $contenu contenu de l'article
//     */
//
//     public function modifier(string $titre, string $contenu) : void {
//         //validation des données
//         if(!self::validation($titre, $contenu)) {
//             return;
//         }
//
//         //modification en BDD
//         $edit = App::$db->prepare(
//             'UPDATE article SET
//             titre = :titre,
//             contenu = :contenu
//             WHERE id = :id'
//         );
//         $requete = $edit ->execute([
//             'titre' => trim($titre),
//             'contenu' => trim($contenu),
//             'id' => $this->id
//         ]);
//
//         // si la requete sql n'est pas exécutée correctement
//         if(!$requete){
//             Messager::message(Messager::MSG_WARNING, 'impossible de modifier l\'article');
//             return;
//         }
//         // si tout est bon, on met à jour les propriétés
//         $this->titre = $titre;
//         $this->contenu = $contenu;
//         Messager::message(Messager::MSG_SUCCESS, 'Article modifié avec succès');
//
//     }
//
//
// } // fin class article
