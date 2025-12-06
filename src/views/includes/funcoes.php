<?php 

function criarThumbnail($caminhoOriginal, $caminhoThumb, $larguraThumb = 300) {

    list($larguraOrig, $alturaOrig, $tipo) = getimagesize($caminhoOriginal);
    
    $alturaThumb = ($alturaOrig / $larguraOrig) * $larguraThumb; 

    switch ($tipo) {
        case IMAGETYPE_JPEG:
            $imgOrig = imagecreatefromjpeg($caminhoOriginal);
            break;
        case IMAGETYPE_PNG:
            $imgOrig = imagecreatefrompng($caminhoOriginal);
            break;
        case IMAGETYPE_GIF:
            $imgOrig = imagecreatefromgif($caminhoOriginal); 
            break;
        default:
            return false;
    }

    // Cria imagem de destino
    $imgThumb = imagecreatetruecolor($larguraThumb, $alturaThumb);

    // Preservar transparência para PNG e GIF
    if ($tipo == IMAGETYPE_PNG || $tipo == IMAGETYPE_GIF) {
        imagecolortransparent($imgThumb, imagecolorallocatealpha($imgThumb, 0, 0, 0, 127));
        imagealphablending($imgThumb, false);
        imagesavealpha($imgThumb, true);
    }

    imagecopyresampled($imgThumb, $imgOrig, 0, 0, 0, 0, $larguraThumb, $alturaThumb, $larguraOrig, $alturaOrig);

    switch ($tipo) {
        case IMAGETYPE_JPEG:
            imagejpeg($imgThumb, $caminhoThumb, 80);
            break;
        case IMAGETYPE_PNG:
            imagepng($imgThumb, $caminhoThumb, 80);
            break;
        case IMAGETYPE_GIF:
            imagegif($imgThumb, $caminhoThumb, 80);
            break;
        
        default:
            break;
    }

    imagedestroy($imgOrig);
    imagedestroy($imgThumb);

    return true;
}

?>