<?php
$errors = [];

if (!isset($_GET['file'])) {
    $errors[] = 'Aucun fichier spécifié.';
    exit;
}

$webpFile = $_GET['file'];

if (!file_exists($webpFile)) {
    $errors[] = 'Fichier non trouvé.';
    exit;
}

// Construire le chemin du fichier original à partir du nom de fichier WebP
$originalFile = 'uploads/' . pathinfo($webpFile, PATHINFO_FILENAME) . '.' . (strpos($webpFile, '.webp') !== false ? 'png' : 'jpg');

// Obtenir la taille du fichier en octets
$originalFileSizeBytes = filesize($originalFile);
$newFileSizeBytes = filesize($webpFile);

// Convertir la taille du fichier en un format lisible (Ko, Mo, etc.)
function formatFileSize($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $unitIndex = 0;
    while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
        $bytes /= 1024;
        $unitIndex++;
    }
    return round($bytes, 2) . ' ' . $units[$unitIndex];
}

$originalFileSize = formatFileSize($originalFileSizeBytes);
$newFileSize = formatFileSize($newFileSizeBytes);
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/style.css">
    <title>Image Convertie</title>
    <script>
        // Si l'utilisateur quitte la page, ses images sont supprimées
        document.addEventListener('visibilitychange', function() {
            // Création des données à envoyer
            var data = new FormData();
            data.append('file', '<?php echo urlencode($webpFile); ?>');
            data.append('original', '<?php echo urlencode($originalFile); ?>');

            // Envoi de la requête avec sendBeacon
            navigator.sendBeacon('cleanup.php', data);
        });
    </script>
</head>

<body>
    <section id="download" class="container">
        <h1>Image Convertie en WebP</h1>
        <img src="<?php echo htmlspecialchars($webpFile); ?>" alt="Image convertie">
        <p><?php echo htmlspecialchars($newFileSize) . " (taille du fichier d'origine : " . htmlspecialchars($originalFileSize) . ")"; ?></p>
        <a href="<?php echo htmlspecialchars($webpFile); ?>" download="image.webp" class="btn">Télécharger l'image</a>
        <a href="index.php" class="btn">Retour à l'accueil</a>
    </section>
</body>

</html>