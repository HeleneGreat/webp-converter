<?php
function convertToWebP($source, $destination, $quality = 80)
{
    $imageInfo = getimagesize($source);
    $mime = $imageInfo['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
            break;
        default:
            throw new Exception('Type d\'image non supporté : ' . $mime);
    }

    if (!imagewebp($image, $destination, $quality)) {
        throw new Exception('Échec de la conversion en WebP.');
    }

    imagedestroy($image);
    return $destination;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($_FILES['image']['name']);
    $webpFile = $uploadDir . pathinfo($uploadFile, PATHINFO_FILENAME) . '.webp';

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        try {
            convertToWebP($uploadFile, $webpFile);
            header('Location: download.php?file=' . urlencode($webpFile));
            exit;
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    } else {
        echo 'Erreur lors du téléchargement de l\'image.';
    }
} else {
    echo 'Aucune image téléchargée.';
}
