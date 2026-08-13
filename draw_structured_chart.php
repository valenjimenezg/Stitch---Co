
<?php
// Canvas Configuration
$width = 2400;
$height = 1650;
$im = imagecreatetruecolor($width, $height);

// Font files
$font_regular = 'C:\\Windows\\Fonts\\segoeui.ttf';
$font_bold = 'C:\\Windows\\Fonts\\segoeuib.ttf';

// Color Palette (Slate / Modern Dark Theme)
$bg_color = imagecolorallocate($im, 15, 23, 42); // Slate 900
$grid_color = imagecolorallocate($im, 30, 41, 59); // Slate 800
$card_bg = imagecolorallocate($im, 30, 41, 59); // Slate 800
$card_border = imagecolorallocate($im, 71, 85, 105); // Slate 600
$text_main = imagecolorallocate($im, 241, 245, 249); // Slate 100
$text_muted = imagecolorallocate($im, 148, 163, 184); // Slate 400
$data_color = imagecolorallocate($im, 56, 189, 248); // Sky 400 (Data Couples)
$control_color = imagecolorallocate($im, 244, 63, 94); // Rose 500 (Control Couples)
$line_color = imagecolorallocate($im, 100, 116, 139); // Slate 500

// Accent Colors for Level 1 Modules
$accents = [
    'root'    => imagecolorallocate($im, 245, 158, 11),  // Amber 500
    'auth'    => imagecolorallocate($im, 99, 102, 241),  // Indigo 500
    'catalog' => imagecolorallocate($im, 244, 63, 94),   // Rose 500
    'sales'   => imagecolorallocate($im, 16, 185, 129),  // Emerald 500
    'admin'   => imagecolorallocate($im, 139, 92, 246),  // Purple 500
    'crm'     => imagecolorallocate($im, 14, 165, 233),  // Sky 500
];

// Fill background
imagefilledrectangle($im, 0, 0, $width, $height, $bg_color);

// Draw grid
for ($x = 0; $x < $width; $x += 40) {
    imageline($im, $x, 0, $x, $height, $grid_color);
}
for ($y = 0; $y < $height; $y += 40) {
    imageline($im, 0, $y, $width, $y, $grid_color);
}

// Helper to draw text with TTF support or fallback
function draw_text($im, $size, $x, $y, $color, $font, $text) {
    $size = (int)$size;
    $x = (int)$x;
    $y = (int)$y;
    if (file_exists($font)) {
        imagettftext($im, $size, 0, $x, $y, $color, $font, $text);
    } else {
        // Fallback
        $gd_font = 2;
        if ($size > 12) $gd_font = 4;
        else if ($size > 10) $gd_font = 3;
        imagestring($im, $gd_font, $x, $y - 6, $text, $color);
    }
}

// Helper to draw rounded rectangles
function draw_rounded_rect($im, $x1, $y1, $x2, $y2, $radius, $color, $filled = false) {
    $x1 = (int)$x1;
    $y1 = (int)$y1;
    $x2 = (int)$x2;
    $y2 = (int)$y2;
    $radius = (int)$radius;
    if ($filled) {
        imagefilledrectangle($im, $x1 + $radius, $y1, $x2 - $radius, $y2, $color);
        imagefilledrectangle($im, $x1, $y1 + $radius, $x2, $y2 - $radius, $color);
        imagefilledarc($im, $x1 + $radius, $y1 + $radius, $radius * 2, $radius * 2, 180, 270, $color, IMG_ARC_PIE);
        imagefilledarc($im, $x2 - $radius, $y1 + $radius, $radius * 2, $radius * 2, 270, 360, $color, IMG_ARC_PIE);
        imagefilledarc($im, $x1 + $radius, $y2 - $radius, $radius * 2, $radius * 2, 90, 180, $color, IMG_ARC_PIE);
        imagefilledarc($im, $x2 - $radius, $y2 - $radius, $radius * 2, $radius * 2, 0, 90, $color, IMG_ARC_PIE);
    } else {
        imageline($im, $x1 + $radius, $y1, $x2 - $radius, $y1, $color);
        imageline($im, $x1 + $radius, $y2, $x2 - $radius, $y2, $color);
        imageline($im, $x1, $y1 + $radius, $x1, $y2 - $radius, $color);
        imageline($im, $x2, $y1 + $radius, $x2, $y2 - $radius, $color);
        imagearc($im, $x1 + $radius, $y1 + $radius, $radius * 2, $radius * 2, 180, 270, $color);
        imagearc($im, $x2 - $radius, $y1 + $radius, $radius * 2, $radius * 2, 270, 360, $color);
        imagearc($im, $x1 + $radius, $y2 - $radius, $radius * 2, $radius * 2, 90, 180, $color);
        imagearc($im, $x2 - $radius, $y2 - $radius, $radius * 2, $radius * 2, 0, 90, $color);
    }
}

// Helper to draw coupling arrows (Data/Control Couples)
function draw_couple($im, $x, $y, $direction, $is_control, $label, $font_regular, $data_color, $control_color, $text_muted) {
    $x = (int)$x;
    $y = (int)$y;
    $color = $is_control ? $control_color : $data_color;
    imagesetthickness($im, 2);
    
    $arrow_len = 22;
    $circle_r = 3;
    
    if ($direction === 'down') {
        // Arrow pointing down: Circle on top, arrow head on bottom
        imageline($im, $x, $y + $circle_r, $x, $y + $arrow_len, $color);
        // Arrow head
        imageline($im, $x, $y + $arrow_len, $x - 4, $y + $arrow_len - 5, $color);
        imageline($im, $x, $y + $arrow_len, $x + 4, $y + $arrow_len - 5, $color);
        // Tail circle
        if ($is_control) {
            imagefilledellipse($im, $x, $y, $circle_r * 2, $circle_r * 2, $color);
        } else {
            imageellipse($im, $x, $y, $circle_r * 2, $circle_r * 2, $color);
        }
        // Label
        draw_text($im, 8, $x + 8, $y + 14, $text_muted, $font_regular, $label);
    } else {
        // Arrow pointing up: Circle on bottom, arrow head on top
        imageline($im, $x, $y + $arrow_len - $circle_r, $x, $y, $color);
        // Arrow head
        imageline($im, $x, $y, $x - 4, $y + 5, $color);
        imageline($im, $x, $y, $x + 4, $y + 5, $color);
        // Tail circle
        if ($is_control) {
            imagefilledellipse($im, $x, $y + $arrow_len, $circle_r * 2, $circle_r * 2, $color);
        } else {
            imageellipse($im, $x, $y + $arrow_len, $circle_r * 2, $circle_r * 2, $color);
        }
        // Label
        draw_text($im, 8, $x + 8, $y + 14, $text_muted, $font_regular, $label);
    }
}

// 1. Draw Root Box
$root_w = 320;
$root_h = 60;
$root_x = 1200 - ($root_w / 2);
$root_y = 40;
draw_rounded_rect($im, $root_x, $root_y, $root_x + $root_w, $root_y + $root_h, 10, $card_bg, true);
draw_rounded_rect($im, $root_x, $root_y, $root_x + $root_w, $root_y + $root_h, 10, $accents['root'], false);
draw_text($im, 12, $root_x + 35, $root_y + 36, $text_main, $font_bold, "SISTEMA STITCH & CO.");

// 2. Define Level 1 Modules (Columns)
$modules = [
    'auth' => [
        'title' => 'Modulo de Autenticacion',
        'center_x' => 300,
        'accent' => 'auth',
        'children' => [
            ['title' => 'Registro de Usuario', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'datos_registro'], ['dir' => 'up', 'ctrl' => true, 'lbl' => 'registro_ok']]],
            ['title' => 'Iniciar Sesion', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'credenciales'], ['dir' => 'up', 'ctrl' => true, 'lbl' => 'token_sesion']]],
            ['title' => 'Recuperar Contrasena (OTP)', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'email_usuario'], ['dir' => 'up', 'ctrl' => true, 'lbl' => 'otp_enviado']]]
        ]
    ],
    'catalog' => [
        'title' => 'Modulo de Catalogo',
        'center_x' => 750,
        'accent' => 'catalog',
        'children' => [
            ['title' => 'Explorar Catalogo', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'filtros'], ['dir' => 'up', 'ctrl' => false, 'lbl' => 'lista_productos']]],
            ['title' => 'Buscar Productos', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'query_busqueda'], ['dir' => 'up', 'ctrl' => false, 'lbl' => 'productos_coincidentes']]],
            ['title' => 'Moderar Resenas', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'comentario_id'], ['dir' => 'up', 'ctrl' => true, 'lbl' => 'aprobado_ok']]]
        ]
    ],
    'sales' => [
        'title' => 'Modulo de Ventas',
        'center_x' => 1200,
        'accent' => 'sales',
        'children' => [
            ['title' => 'Gestionar Carrito', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'item_variante'], ['dir' => 'up', 'ctrl' => false, 'lbl' => 'items_carrito']]],
            ['title' => 'Iniciar Checkout', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'carrito_id'], ['dir' => 'up', 'ctrl' => false, 'lbl' => 'monto_total']]],
            ['title' => 'Registrar Pago Movil', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'datos_pago'], ['dir' => 'up', 'ctrl' => true, 'lbl' => 'pago_conciliado']]],
            ['title' => 'Generar Factura PDF', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'orden_id'], ['dir' => 'up', 'ctrl' => false, 'lbl' => 'archivo_pdf']]]
        ]
    ],
    'admin' => [
        'title' => 'Modulo de Administracion',
        'center_x' => 1650,
        'accent' => 'admin',
        'children' => [
            ['title' => 'CRUD Prod. y Variantes', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'datos_producto'], ['dir' => 'up', 'ctrl' => true, 'lbl' => 'guardado_ok']]],
            ['title' => 'Logs de Inventario', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'movimiento_inv'], ['dir' => 'up', 'ctrl' => true, 'lbl' => 'log_registrado']]],
            ['title' => 'Configurar Tasa BCV', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'nueva_tasa'], ['dir' => 'up', 'ctrl' => true, 'lbl' => 'tasa_actualizada']]]
        ]
    ],
    'crm' => [
        'title' => 'Modulo CRM y Soporte',
        'center_x' => 2100,
        'accent' => 'crm',
        'children' => [
            ['title' => 'Consultar Chatbot', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'pregunta_cliente'], ['dir' => 'up', 'ctrl' => false, 'lbl' => 'respuesta_bot']]],
            ['title' => 'Alertas de Stock', 'couples' => [['dir' => 'down', 'ctrl' => false, 'lbl' => 'variante_id'], ['dir' => 'up', 'ctrl' => true, 'lbl' => 'alerta_enviada']]]
        ]
    ]
];

// 3. Draw Level 1 modules and connection lines from Root
$level1_y = 200;
$level1_w = 280;
$level1_h = 60;

// Draw Root-to-Level1 Bus
imagesetthickness($im, 2);
imageline($im, 1200, 100, 1200, 150, $line_color); // Center vertical line
imageline($im, 300, 150, 2100, 150, $line_color); // Horizontal bus

foreach ($modules as $key => $mod) {
    $cx = $mod['center_x'];
    
    // Draw vertical drops to each module
    imageline($im, $cx, 150, $cx, $level1_y, $line_color);
    
    // Draw Module Box
    $bx1 = $cx - ($level1_w / 2);
    $by1 = $level1_y;
    $bx2 = $cx + ($level1_w / 2);
    $by2 = $level1_y + $level1_h;
    
    draw_rounded_rect($im, $bx1, $by1, $bx2, $by2, 10, $card_bg, true);
    draw_rounded_rect($im, $bx1, $by1, $bx2, $by2, 10, $accents[$mod['accent']], false);
    
    // Text
    $text_x = $cx - (strlen($mod['title']) * 3.8);
    draw_text($im, 10, $text_x, $by1 + 35, $text_main, $font_bold, strtoupper($mod['title']));
    
    // 4. Draw Level 2 children
    $child_w = 250;
    $child_h = 50;
    $start_child_y = 360;
    $y_gap = 140; // Spacing between rows of children
    
    $last_y = $by2;
    $num_children = count($mod['children']);
    
    // Draw vertical trunk for children
    $total_trunk_y = $start_child_y + (($num_children - 1) * $y_gap) + ($child_h / 2);
    imageline($im, $cx, $by2, $cx, $total_trunk_y, $line_color);
    
    for ($i = 0; $i < $num_children; $i++) {
        $child = $mod['children'][$i];
        $cy = $start_child_y + ($i * $y_gap);
        
        // Draw Child Box
        $cx1 = $cx - ($child_w / 2);
        $cy1 = $cy;
        $cx2 = $cx + ($child_w / 2);
        $cy2 = $cy + $child_h;
        
        draw_rounded_rect($im, $cx1, $cy1, $cx2, $cy2, 8, $card_bg, true);
        draw_rounded_rect($im, $cx1, $cy1, $cx2, $cy2, 8, $card_border, false);
        
        // Child Text
        $child_text_x = $cx - (strlen($child['title']) * 3.5);
        draw_text($im, 9, $child_text_x, $cy1 + 30, $text_main, $font_regular, $child['title']);
        
        // Draw couples in the trunk gap above this child
        $gap_top_y = ($i === 0) ? $by2 : $start_child_y + (($i - 1) * $y_gap) + $child_h;
        $gap_bottom_y = $cy;
        $mid_gap_y = ($gap_top_y + $gap_bottom_y) / 2;
        
        // Place first couple left of the trunk, second couple right of the trunk
        if (isset($child['couples'][0])) {
            $cp1 = $child['couples'][0];
            draw_couple($im, $cx - 45, $mid_gap_y - 11, $cp1['dir'], $cp1['ctrl'], $cp1['lbl'], $font_regular, $data_color, $control_color, $text_muted);
        }
        if (isset($child['couples'][1])) {
            $cp2 = $child['couples'][1];
            draw_couple($im, $cx + 25, $mid_gap_y - 11, $cp2['dir'], $cp2['ctrl'], $cp2['lbl'], $font_regular, $data_color, $control_color, $text_muted);
        }
    }
}

// 5. Draw Title and Legend
$title_color = imagecolorallocate($im, 255, 255, 255);
draw_text($im, 22, 80, 60, $title_color, $font_bold, "STITCH & CO. - CARTA ESTRUCTURADA");
draw_text($im, 11, 80, 90, $text_muted, $font_regular, "Diseno modular y flujo de datos/control de las funciones del sistema");

// Legend Box
$legend_x1 = 1880;
$legend_y1 = 40;
$legend_x2 = $legend_x1 + 440;
$legend_y2 = $legend_y1 + 80;
draw_rounded_rect($im, $legend_x1, $legend_y1, $legend_x2, $legend_y2, 8, $card_bg, true);
draw_rounded_rect($im, $legend_x1, $legend_y1, $legend_x2, $legend_y2, 8, $card_border, false);

draw_text($im, 10, $legend_x1 + 15, $legend_y1 + 25, $text_main, $font_bold, "LEYENDA:");

// Data couple legend
draw_couple($im, $legend_x1 + 110, $legend_y1 + 18, 'down', false, 'Dato', $font_regular, $data_color, $control_color, $text_muted);
// Control couple legend
draw_couple($im, $legend_x1 + 250, $legend_y1 + 18, 'up', true, 'Control', $font_regular, $data_color, $control_color, $text_muted);

// --- PANEL EXPLICATIVO INFERIOR (Y = 920 a 1600) ---

$table_header_bg = imagecolorallocate($im, 15, 23, 42); // Dark background
$table_border = imagecolorallocate($im, 51, 65, 85);    // Slate 700
$table_row_alt = imagecolorallocate($im, 38, 50, 71);   // Slate 750

// Columna 1: Conceptos Clave
$col1_x1 = 80;
$col1_y1 = 920;
$col1_x2 = 780;
$col1_y2 = 1600;

draw_rounded_rect($im, $col1_x1, $col1_y1, $col1_x2, $col1_y2, 10, $card_bg, true);
draw_rounded_rect($im, $col1_x1, $col1_y1, $col1_x2, $col1_y2, 10, $card_border, false);

draw_text($im, 12, $col1_x1 + 25, $col1_y1 + 40, $text_main, $font_bold, "CONCEPTOS DE DISENO ESTRUCTURADO");
imageline($im, $col1_x1 + 25, $col1_y1 + 55, $col1_x2 - 25, $col1_y1 + 55, $table_border);

// Concept details
$concepts = [
    ["DIAGRAMA ESTRUCTURADO", "Muestra la jerarquia de modulos y la comunicacion entre ellos", "en el software Stitch & Co."],
    ["MODULACION", "La descomposicion del sistema en modulos independientes", "con tareas claras y acotadas."],
    ["ACOPLAMIENTO", "Medida de la interdependencia entre modulos.", "Buscamos BAJO ACOPLAMIENTO (parametros explicitos)."],
    ["COHESION", "Medida de que tan enfocadas estan las tareas internas", "de un modulo. Buscamos ALTA COHESION FUNCIONAL."],
    ["DATA COUPLE (Acoplamiento Datos)", "Intercambio de informacion necesaria para el proceso.", "Representado por: (o->) Flecha con circulo vacio."],
    ["CONTROL COUPLE (Acoplamiento Control)", "Transmision de senales de estado o flags de decision.", "Representado por: (*->) Flecha con circulo relleno."]
];

$cy_concept = $col1_y1 + 85;
foreach ($concepts as $c) {
    draw_text($im, 10, $col1_x1 + 25, $cy_concept, $accents['root'], $font_bold, $c[0]);
    draw_text($im, 9, $col1_x1 + 25, $cy_concept + 20, $text_main, $font_regular, $c[1]);
    draw_text($im, 9, $col1_x1 + 25, $cy_concept + 38, $text_main, $font_regular, $c[2]);
    $cy_concept += 80;
}

// Columna 2: Diccionario de Datos y Control (1/2)
$col2_x1 = 840;
$col2_y1 = 920;
$col2_x2 = 1580;
$col2_y2 = 1600;

draw_rounded_rect($im, $col2_x1, $col2_y1, $col2_x2, $col2_y2, 10, $card_bg, true);
draw_rounded_rect($im, $col2_x1, $col2_y1, $col2_x2, $col2_y2, 10, $card_border, false);

draw_text($im, 12, $col2_x1 + 25, $col2_y1 + 40, $text_main, $font_bold, "DICCIONARIO DE VARIABLES Y ACOPLAMIENTO (1/2)");
imageline($im, $col2_x1 + 25, $col2_y1 + 55, $col2_x2 - 25, $col2_y1 + 55, $table_border);

// Header coordinates
$t1_x = $col2_x1 + 25;
$t_y = $col2_y1 + 75;
$row_h = 52;

// Table header
draw_text($im, 9.5, $t1_x, $t_y + 15, $text_muted, $font_bold, "Modulo / Operacion");
draw_text($im, 9.5, $t1_x + 200, $t_y + 15, $text_muted, $font_bold, "Variable");
draw_text($im, 9.5, $t1_x + 360, $t_y + 15, $text_muted, $font_bold, "Tipo");
draw_text($im, 9.5, $t1_x + 440, $t_y + 15, $text_muted, $font_bold, "Descripcion");

imageline($im, $col2_x1 + 25, $t_y + 25, $col2_x2 - 25, $t_y + 25, $table_border);

$row_y = $t_y + 26;

// Rows data for Col 2
$col2_rows = [
    ["Registro de Usuario", "datos_registro", "Dato (E)", "Nombre, email, doc, dir, tlf."],
    ["Registro de Usuario", "registro_ok", "Control (S)", "Confirmacion de registro."],
    ["Iniciar Sesion", "credenciales", "Dato (E)", "Email y contrasena del usuario."],
    ["Iniciar Sesion", "token_sesion", "Control (S)", "ID de sesion o token temporal."],
    ["Recuperar Contrasena", "email_usuario", "Dato (E)", "Destinatario del codigo OTP."],
    ["Recuperar Contrasena", "otp_enviado", "Control (S)", "Bandeja de exito del despacho OTP."],
    ["Explorar Catalogo", "filtros", "Dato (E)", "Filtros de busqueda y categoria."],
    ["Explorar Catalogo", "lista_productos", "Dato (S)", "Array de productos formateados."],
    ["Buscar Productos", "query_busqueda", "Dato (E)", "Cadena de texto de consulta."],
    ["Consultar Chatbot", "pregunta_cliente", "Dato (E)", "Pregunta libre del usuario en chat."]
];

$idx = 0;
foreach ($col2_rows as $row) {
    if ($idx % 2 === 0) {
        imagefilledrectangle($im, (int)($col2_x1 + 20), (int)($row_y), (int)($col2_x2 - 20), (int)($row_y + $row_h), $table_row_alt);
    }
    
    // Draw cells
    draw_text($im, 9, $t1_x, $row_y + 32, $text_main, $font_regular, $row[0]);
    draw_text($im, 9, $t1_x + 200, $row_y + 32, $data_color, $font_bold, $row[1]);
    
    $tipo_color = (strpos($row[2], 'Control') !== false) ? $control_color : $data_color;
    draw_text($im, 9, $t1_x + 360, $row_y + 32, $tipo_color, $font_bold, $row[2]);
    draw_text($im, 8.5, $t1_x + 440, $row_y + 32, $text_main, $font_regular, $row[3]);
    
    // Draw separating line
    imageline($im, $col2_x1 + 25, $row_y + $row_h, $col2_x2 - 25, $row_y + $row_h, $table_border);
    
    $row_y += $row_h;
    $idx++;
}

// Columna 3: Diccionario de Datos y Control (2/2)
$col3_x1 = 1640;
$col3_y1 = 920;
$col3_x2 = 2380;
$col3_y2 = 1600;

draw_rounded_rect($im, $col3_x1, $col3_y1, $col3_x2, $col3_y2, 10, $card_bg, true);
draw_rounded_rect($im, $col3_x1, $col3_y1, $col3_x2, $col3_y2, 10, $card_border, false);

draw_text($im, 12, $col3_x1 + 25, $col3_y1 + 40, $text_main, $font_bold, "DICCIONARIO DE VARIABLES Y ACOPLAMIENTO (2/2)");
imageline($im, $col3_x1 + 25, $col3_y1 + 55, $col3_x2 - 25, $col3_y1 + 55, $table_border);

// Header coordinates
$t2_x = $col3_x1 + 25;
$t2_y = $col3_y1 + 75;

// Table header
draw_text($im, 9.5, $t2_x, $t2_y + 15, $text_muted, $font_bold, "Modulo / Operacion");
draw_text($im, 9.5, $t2_x + 200, $t2_y + 15, $text_muted, $font_bold, "Variable");
draw_text($im, 9.5, $t2_x + 360, $t2_y + 15, $text_muted, $font_bold, "Tipo");
draw_text($im, 9.5, $t2_x + 440, $t2_y + 15, $text_muted, $font_bold, "Descripcion");

imageline($im, $col3_x1 + 25, $t2_y + 25, $col3_x2 - 25, $t2_y + 25, $table_border);

$row_y2 = $t2_y + 26;

// Rows data for Col 3
$col3_rows = [
    ["Gestionar Carrito", "item_variante", "Dato (E)", "ID variante y cantidad a modificar."],
    ["Gestionar Carrito", "items_carrito", "Dato (S)", "Lista de items actualizados."],
    ["Iniciar Checkout", "carrito_id", "Dato (E)", "ID de sesion del carro de compras."],
    ["Iniciar Checkout", "monto_total", "Dato (S)", "Suma calculada en bolivares y USD."],
    ["Registrar Pago Movil", "datos_pago", "Dato (E)", "Telefono, banco, referencia del pago."],
    ["Registrar Pago Movil", "pago_conciliado", "Control (S)", "Estado de conciliacion contra banco."],
    ["Generar Factura PDF", "orden_id", "Dato (E)", "ID de orden finalizada."],
    ["CRUD Prod. y Variantes", "datos_producto", "Dato (E)", "Campos del producto a guardar."],
    ["Configurar Tasa BCV", "nueva_tasa", "Dato (E)", "Precio de cambio oficial del BCV."],
    ["Alertas de Stock", "alerta_enviada", "Control (S)", "Confirmacion de envio de email de stock."]
];

$idx2 = 0;
foreach ($col3_rows as $row) {
    if ($idx2 % 2 === 0) {
        imagefilledrectangle($im, (int)($col3_x1 + 20), (int)($row_y2), (int)($col3_x2 - 20), (int)($row_y2 + $row_h), $table_row_alt);
    }
    
    // Draw cells
    draw_text($im, 9, $t2_x, $row_y2 + 32, $text_main, $font_regular, $row[0]);
    draw_text($im, 9, $t2_x + 200, $row_y2 + 32, $data_color, $font_bold, $row[1]);
    
    $tipo_color = (strpos($row[2], 'Control') !== false) ? $control_color : $data_color;
    draw_text($im, 9, $t2_x + 360, $row_y2 + 32, $tipo_color, $font_bold, $row[2]);
    draw_text($im, 8.5, $t2_x + 440, $row_y2 + 32, $text_main, $font_regular, $row[3]);
    
    // Draw separating line
    imageline($im, $col3_x1 + 25, $row_y2 + $row_h, $col3_x2 - 25, $row_y2 + $row_h, $table_border);
    
    $row_y2 += $row_h;
    $idx2++;
}

// Output/Save the image
imagepng($im, "carta_estructurada.png");
imagedestroy($im);

echo "SUCCESS: Structured Chart rendered to 'carta_estructurada.png' successfully.";
?>
