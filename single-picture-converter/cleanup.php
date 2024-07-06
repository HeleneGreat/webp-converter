<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['file']) && isset($_POST['original'])) {
        $webpFile = urldecode($_POST['file']);
        $originalFile = urldecode($_POST['original']);

        // Vérifiez si les fichiers existent et ont les permissions nécessaires pour être supprimés
        $errors = [];

        if (file_exists($webpFile)) {
            if (!unlink($webpFile)) {
                $errors[] = "Erreur lors de la suppression du fichier WebP : $webpFile";
            }
        } else {
            $errors[] = "Le fichier WebP n'existe pas : $webpFile";
        }

        if (file_exists($originalFile)) {
            if (!unlink($originalFile)) {
                $errors[] = "Erreur lors de la suppression du fichier original : $originalFile";
            }
        } else {
            $errors[] = "Le fichier original n'existe pas : $originalFile";
        }

        if (!empty($errors)) {
            // Affiche les erreurs pour le débogage
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        } else {
            echo "Fichiers supprimés avec succès.";
        }
    } else {
        echo 'Paramètres manquants.';
    }
} else {
    echo 'Méthode de requête incorrecte.';
}
