<?php
// Fichier de configuration personnalisé à inclure à la fin du fichier de configuration du framework : bootsrap.php

//Connexion à la base de données
App\App::dbConnect();

// Fonction à utiliser pour générer les messages.
// la fonction mise en argument de setOutpuHandler est une fonction de type callback car elle est passé en argument et est transformé en variable dans la class Messager
Phunder\Core\Messager::setOutputHandler(function ($type, $contenu){
    return '<div class="alert alert-' . $type . '" role="alert">' . $contenu . '</div>';
});
