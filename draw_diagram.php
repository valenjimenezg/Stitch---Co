<?php
// Configuration
$width = 1300;
$height = 1100;

// Create image canvas
$im = imagecreatetruecolor($width, $height);

// Define Colors
$white = imagecolorallocate($im, 255, 255, 255);
$grid_color = imagecolorallocate($im, 240, 243, 248);
$blue_staruml = imagecolorallocate($im, 0, 93, 179);  // Classic StarUML blue
$black = imagecolorallocate($im, 0, 0, 0);
$ellipse_fill = imagecolorallocate($im, 244, 246, 252); // Light blue-gray fill
$actor_head_fill = imagecolorallocate($im, 244, 246, 252);

// Fill canvas with white background
imagefilledrectangle($im, 0, 0, $width, $height, $white);

// Draw StarUML style grid
for ($x = 0; $x < $width; $x += 20) {
    imageline($im, $x, 0, $x, $height, $grid_color);
}
for ($y = 0; $y < $height; $y += 20) {
    imageline($im, 0, $y, $width, $y, $grid_color);
}

// Helper to draw thick outlines (StarUML uses 1px-2px outlines)
function draw_thick_rect($im, $x1, $y1, $x2, $y2, $thickness, $color) {
    for ($i = 0; $i < $thickness; $i++) {
        imagerectangle($im, $x1 + $i, $y1 + $i, $x2 - $i, $y2 - $i, $color);
    }
}

// Draw System Boundary Box (Límite del Sistema)
draw_thick_rect($im, 250, 30, 950, 1070, 2, $blue_staruml);

// Write Boundary Title (GD built-in font size 4 is ~8x16px)
imagestring($im, 4, 270, 45, "Limite del Sistema: Stitch & Co.", $blue_staruml);

// Draw Stick Figure Actor function
function draw_actor_figure($im, $cx, $cy, $label, $blue, $black, $head_fill) {
    // 1. Draw head (circle)
    imageellipse($im, $cx, $cy - 30, 30, 30, $blue);
    // Fill head background
    imagefilledellipse($im, $cx, $cy - 30, 28, 28, $head_fill);

    // 2. Spine (body)
    imageline($im, $cx, $cy - 15, $cx, $cy + 30, $blue);
    imageline($im, $cx - 1, $cy - 15, $cx - 1, $cy + 30, $blue); // thicken slightly

    // 3. Arms
    imageline($im, $cx - 25, $cy, $cx + 25, $cy, $blue);
    imageline($im, $cx - 25, $cy - 1, $cx + 25, $cy - 1, $blue);

    // 4. Legs
    imageline($im, $cx, $cy + 30, $cx - 15, $cy + 70, $blue);
    imageline($im, $cx, $cy + 30, $cx + 15, $cy + 70, $blue);
    imageline($im, $cx - 1, $cy + 30, $cx - 16, $cy + 70, $blue); // thicken
    imageline($im, $cx + 1, $cy + 30, $cx + 16, $cy + 70, $blue); // thicken

    // 5. Labels
    // GD Font 3 is ~7x13px
    imagestring($im, 3, (int)($cx - (strlen("<<Actor>>") * 7) / 2), $cy + 85, "<<Actor>>", $black);
    // GD Font 4 is ~8x16px
    imagestring($im, 4, (int)($cx - (strlen($label) * 8) / 2), $cy + 105, $label, $blue);
}

// Draw Cliente on the left
draw_actor_figure($im, 120, 500, "Cliente", $blue_staruml, $black, $actor_head_fill);

// Draw Administrador on the right
draw_actor_figure($im, 1180, 500, "Administrador", $blue_staruml, $black, $actor_head_fill);

// Use Cases Definitions with clean positioning inside system boundary (15 Use Cases matching structured chart)
$use_cases = [
    // Column 1 (Cliente-only: cx = 450)
    1  => ["name" => "UC-01: Registro de Usuario", "cx" => 450, "cy" => 100],
    4  => ["name" => "UC-04: Explorar Catálogo", "cx" => 450, "cy" => 220],
    5  => ["name" => "UC-05: Buscar Productos", "cx" => 450, "cy" => 340],
    7  => ["name" => "UC-07: Gestionar Carrito", "cx" => 450, "cy" => 460],
    8  => ["name" => "UC-08: Iniciar Checkout", "cx" => 450, "cy" => 580],
    9  => ["name" => "UC-09: Registrar Pago Móvil", "cx" => 450, "cy" => 700],
    14 => ["name" => "UC-14: Consultar Chatbot", "cx" => 450, "cy" => 820],
    15 => ["name" => "UC-15: Alertas de Stock", "cx" => 450, "cy" => 940],
    
    // Column 2 (Shared & Admin-only: cx = 800)
    2  => ["name" => "UC-02: Iniciar Sesión", "cx" => 800, "cy" => 120],
    3  => ["name" => "UC-03: Recuperar Contraseña (OTP)", "cx" => 800, "cy" => 250],
    10 => ["name" => "UC-10: Generar Factura PDF", "cx" => 800, "cy" => 380],
    6  => ["name" => "UC-06: Moderar Reseñas", "cx" => 800, "cy" => 510],
    11 => ["name" => "UC-11: CRUD Prod. y Variantes", "cx" => 800, "cy" => 640],
    12 => ["name" => "UC-12: Logs de Inventario", "cx" => 800, "cy" => 770],
    13 => ["name" => "UC-13: Configurar Tasa BCV", "cx" => 800, "cy" => 900]
];

// Connection lines - drawing connection to boundaries of Use Case ovals (width 240 => radius 120)
$cliente_cx = 135;
$cliente_cy = 500;

// Connect Cliente to Column 1 Use Cases
foreach ([1, 4, 5, 7, 8, 9, 14, 15] as $id) {
    $uc = $use_cases[$id];
    imageline($im, $cliente_cx, $cliente_cy, $uc["cx"] - 120, $uc["cy"], $blue_staruml);
}
// Connect Cliente to Shared Column 2 Use Cases
foreach ([2, 3, 10] as $id) {
    $uc = $use_cases[$id];
    imageline($im, $cliente_cx, $cliente_cy, $uc["cx"] - 120, $uc["cy"], $blue_staruml);
}

// Connect Administrador to Column 2 Use Cases (Shared & Admin-only)
$admin_cx = 1165;
$admin_cy = 500;
foreach ([2, 3, 10, 6, 11, 12, 13] as $id) {
    $uc = $use_cases[$id];
    imageline($im, $admin_cx, $admin_cy, $uc["cx"] + 120, $uc["cy"], $blue_staruml);
}

// Draw the ovals and print labels inside them
foreach ($use_cases as $uc) {
    // Fill ellipse background
    imagefilledellipse($im, $uc["cx"], $uc["cy"], 240, 50, $ellipse_fill);
    
    // Draw thick ellipse outline (StarUML standard)
    imageellipse($im, $uc["cx"], $uc["cy"], 240, 50, $blue_staruml);
    imageellipse($im, $uc["cx"], $uc["cy"], 238, 48, $blue_staruml); // double pixel outline
    
    // Center label inside oval
    $text = $uc["name"];
    // Centering calculation: Font size 3 width is 7px per character
    $tx = (int)($uc["cx"] - (strlen($text) * 7) / 2);
    $ty = $uc["cy"] - 6;
    imagestring($im, 3, $tx, $ty, $text, $black);
}

// Save image directly to the workspace as required
imagepng($im, "diagrama_casos_uso.png");

// Clean memory
imagedestroy($im);

echo "SUCCESS: StarUML diagram rendered to 'diagrama_casos_uso.png' successfully.";
?>
