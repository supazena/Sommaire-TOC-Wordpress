/* Sommaire de page basé sur les balise Hn START */
/* Ce code est à insérer dans le functions.php du thème enfant de votre installation wordpress */


function generate_sommaire($atts) {
    // Récupérez les attributs du shortcode
    $attributes = shortcode_atts(
        array(
            'hn' => '2-4', // Valeur par défaut du range (H2 à H4)
            'class_prefix' => 'sommaire-h', // Préfixe de la classe CSS pour chaque niveau Hn
        ),
        $atts
    );

    // Extraire les valeurs minimales et maximales du range
    list($min, $max) = explode('-', $attributes['hn']);

    // Récupérez le contenu de la page
    $content = get_the_content();

    // Utilisez une expression régulière pour extraire les titres Hn avec le range spécifié
    $pattern = "/<h([$min-$max]{1})[^>]*>(.*?)<\/h([$min-$max]{1})>/i";
    preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

    // Générer la table des matières
    // Ajouter un titre automatique entre <nav> et <ul> pour annoncer votre table des matières si besoin, ex <p class='titresommaire'>Sommaire</p>
    $sommaire = "<nav class='navsommaire'><ul class='sommaire'>";
    foreach ($matches as $match) {
        $level = $match[1];
        $title = $match[2];
        $slug = sanitize_title($title);
        $css_class = $attributes['class_prefix'] . $level;
        $sommaire .= "<li class='{$css_class}'><a href='#{$slug}'>{$title}</a></li>";
    }
    $sommaire .= "</ul></nav>";

    return $sommaire;
}

function add_id_to_headings($content) {
    // Définir le range de Hn à traiter (dans cet exemple, de H1 à H6)
    $min = 1;
    $max = 6;

    // Utilisez une expression régulière pour ajouter des ID aux titres Hn
    $pattern = "/<h([$min-$max]{1})[^>]*>(.*?)<\/h([$min-$max]{1})>/i";
    $updated_content = preg_replace_callback($pattern, function($match) {
        $level = $match[1];
        $title = $match[2];
        $slug = sanitize_title($title);
        return "<h{$level} id='{$slug}'>{$title}</h{$level}>";
    }, $content);

    return $updated_content;
}

add_shortcode('sommaire', 'generate_sommaire');
add_filter('the_content', 'add_id_to_headings', 10);


/* Sommaire de page basé sur les balise Hn END */
