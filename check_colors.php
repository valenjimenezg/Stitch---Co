<?php
$images = [
    'public/img/productos/1774760434_Sb9x8YWkSiQnfqfuTHvtGaThZhiq6OYbnrCK9fxk.webp',
    'public/img/productos/1774760451_tmLd5qEQ8AazwtckJX92bSal7yoSbHQm4V4CYuGn.webp',
    'public/img/productos/1774760462_LaB27T0SBsDkg1xVzpOrzQ27HeRnK3Zz4gq2a8x8.webp',
    'public/img/productos/hilo 100 algodon.png'
];

foreach ($images as $img) {
    echo "Processing $img...\n";
    if (!file_exists($img)) {
        echo "File not found.\n";
        continue;
    }
    
    // detect average color by resizing to 1x1
    $ext = pathinfo($img, PATHINFO_EXTENSION);
    if ($ext == 'webp') {
        $source = @imagecreatefromwebp($img);
    } elseif ($ext == 'png') {
        $source = @imagecreatefrompng($img);
    } elseif ($ext == 'jpg' || $ext == 'jpeg') {
        $source = @imagecreatefromjpeg($img);
    } else {
        continue;
    }
    
    if (!$source) {
        echo "Could not read image.\n";
        continue;
    }
    
    $thumb = imagecreatetruecolor(1, 1);
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, 1, 1, imagesx($source), imagesy($source));
    $color = imagecolorat($thumb, 0, 0);
    $r = ($color >> 16) & 0xFF;
    $g = ($color >> 8) & 0xFF;
    $b = $color & 0xFF;
    
    echo "RGB: $r, $g, $b\n\n";
}
