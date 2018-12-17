<?php

namespace Categorie;

use Phunder\Core\Messager;
use App\App;


class Categorie {

    private $id_cat;
    private $nom_cat;
    private $date_creation;
    private $description_cat;

    public function __construct(int $id_cat){
        //verifie que l'id correspond à un article, à une id existante
        $categorie = App::$db->prepare('SELECT * FROM categories WHERE id_cat = :id_cat');
        $categorie->bindParam(':id_cat', $id_cat, \PDO::PARAM_INT);
        $categorie->execute();

        // si c'est pas le cas -> erreur
        if($categorie->rowCount() != 1){
            throw new \Exception("Catégorie inconnu");
        }
        // définir les propriétés
        $categorie = $categorie->fetch(\PDO::FETCH_ASSOC);
        $this->id_cat = $categorie['id_cat'];
        $this->nom_cat = $categorie['nom_cat'];
        $this->date_creation = $categorie['date_creation'];
        $this->description_cat= $categorie['description_cat'];
    } // fin __construct

    // pour pouvoir lire ce que renvoit les construct
    public function getId() : int {
        return $this->id_cat;
    }

    public function getNom() : string {
        return $this->nom_cat;
    }

    public function getDateCrea() : string {
        return $this->date_creation;
    }

    public function getDescription() : string{
        return $this->description_cat;
    }

    public function dateFr() : string {
        return(new \DateTime($this->date_publication))->format('d/m/Y');
    }


    /**
    * 4 - Obtenir une liste de catégories
    *
    */

     public static function liste() : array {
         $liste = App::$db->query(
             'SELECT
             id_cat,
             nom_cat,
             date_creation,
             description_cat,
             DATE_FORMAT(date_creation, "%d/%m/%Y") AS date_fr
             FROM categories
             ORDER BY
             nom_cat ASC'
         );
         return $liste->fetchAll(\PDO::FETCH_ASSOC);
     }

        /*
         * 1 - Valider  l'ajout des catégories
         */

        private static function validation(string $nom_cat, string $description_cat) : bool{
            //tableau d'erreurs possibles
            $erreurs = [
                'nom catégorie vide' => empty(trim($nom_cat)),
                'description vide' => empty(trim($description_cat))
            ];
            // si erreur trouvée
            if(in_array(true, $erreurs)){
                Messager::message(Messager::MSG_WARNING, array_search(true, $erreurs));
                return false;
            }
            return true;
        }


     /**
      *  3 - Ajouter une catégorie
      */

     public static function ajouter(string $nom_cat, string $description_cat) : bool
     {
         //vérification validation des données
         if (!self::validation($nom_cat, $description_cat)){
             return false;
         }

         //Insertion en BDD
         $insert = App::$db->prepare(
             'INSERT INTO categories (
                 nom_cat,
                 date_creation,
                 description_cat
             ) VALUES (
                 :nom_cat,
                 NOW(),
                 :description_cat
             )'
         );


         //execution, execute() retourne un bool
         // les index correspondent aux values de la requete insert pour dire tel marqueur va avec tel $_POST
         $resultat = $insert->execute([
             "nom_cat" => $nom_cat,
             "description_cat" => $description_cat
         ]);
         // si l'insertion ne vaut pas true ->msg erreur
         if(!$resultat){
             Messager::message(Messager::MSG_WARNING, 'La catégorie  n\'a pas pu etre ajouté');
             return false;
         }
         Messager::message(Messager::MSG_SUCCESS, 'Catégorie ajouté');
         return true;

     } // fin fonction ajouter


    /**
    * 2 - Supprimer  une catégorie
    */
    public function supprimer()
    {
        $supp = App::$db->prepare('DELETE FROM categories WHERE id_cat = :id_cat');
        $supp->bindParam(':id_cat', $this->id_cat, \PDO::PARAM_INT);

        $resultat = $supp->execute();
        if(!$resultat){
            Messager::message(Messager::MSG_WARNING, 'Impossible de supprimer la categorie');
        }
        Messager::message(Messager::MSG_SUCCESS, 'categorie supprimée');
    }


     /**
     * 5 - Modifier article
     * @param string $titre titre de l'article
     * @return void $contenu contenu de l'article
     */

    public function modifier(string $nom_cat, string $description_cat) : void {
        //validation des données
        if(!self::validation($nom_cat, $description_cat)) {
            return;
        }

        //modification en BDD
        $edit = App::$db->prepare(
            'UPDATE categories SET
            nom_cat = :nom_cat,
            description_cat = :description_cat
            WHERE id_cat = :id_cat'
        );
        $requete = $edit ->execute([
            'nom_cat' => trim($nom_cat),
            'description_cat' => trim($description_cat),
            'id_cat' => $this->id_cat
        ]);

        // si la requete sql n'est pas exécutée correctement
        if(!$requete){
            Messager::message(Messager::MSG_WARNING, 'impossible de modifier la catégorie');
            return;
        }
        // si tout est bon, on met à jour les propriétés
        $this->nom_cat = $nom_cat;
        $this->description_cat = $description_cat;
        Messager::message(Messager::MSG_SUCCESS, 'catégorie modifiée avec succès');

    }
//
//
} // fin class catégorie
