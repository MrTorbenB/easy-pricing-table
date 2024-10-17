<?php
/*
Plugin Name: Easy Pricing Table with Admin Customization
Plugin URI:  https://torbenb.info/download
Description: Erstelle moderne Preistabellen mit Shadowboxen und lass den Benutzer die Eingaben für bis zu 3 Preistabellen im Admin-Bereich vornehmen.
Version:     1.1
Author:      TorbenB
Author URI:  https://torbenb.info
*/

// Funktion, um die Preistabelle anzuzeigen
function easy_pricing_table_shortcode() {
    // Hole die gespeicherten Preistabellen-Daten aus der Datenbank
    $pricing_tables = get_option('easy_pricing_tables', array());

    // HTML-Layout für die Preistabelle
    $output = '<div class="pricing">';
    foreach ($pricing_tables as $table) {
        if (!empty($table['plan']) && !empty($table['price']) && !empty($table['features'])) {
            $output .= '
            <div class="plan">
                <h2>' . esc_html($table['plan']) . '</h2>
                <div class="price">' . esc_html($table['price']) . '</div>
                <ul class="features">';
            
            $features = explode("\n", $table['features']);
            foreach ($features as $feature) {
                $output .= '<li><i class="fas fa-check-circle"></i> ' . esc_html($feature) . '</li>';
            }
            
            $output .= '</ul>
                <a href="' . esc_url($table['link']) . '" class="button">Jetzt kaufen</a>
            </div>';
        }
    }
    $output .= '</div>';

    // Rückgabe der Preistabelle
    return $output;
}

// Shortcode registrieren
add_shortcode('easy_pricing_table', 'easy_pricing_table_shortcode');

// CSS für die Preistabelle hinzufügen
function easy_pricing_table_styles() {
    echo "
    <style>
    @import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Source Sans Pro', sans-serif;
    }
    body {
        background-color: #dff9fb;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    h1 {
        text-align: center;
        margin-top: 2rem;
    }
    p {
        text-align: center;
        margin-bottom: 4rem;
    }
    .pricing {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }
    .plan {
        background-color: #fff;
        padding: 2.5rem;
        margin: 12px;
        border-radius: 5px;
        text-align: center;
        transition: 0.3s;
        cursor: pointer;
        border: 2px solid #6ab04c;
    }
    .plan h2 {
        font-size: 22px;
        margin-bottom: 12px;
    }
    .plan .price {
        margin-bottom: 1rem;
        font-size: 30px;
    }
    .features {
        list-style-type: none;
        text-align: left;
    }
    .features li {
        margin: 8px;
    }
    .features .fas {
        margin-right: 4px;
    }
    .features .fa-check-circle {
        color: #6ab04c;
    }
    .features .fa-times-circle {
        color: #eb4d4b;
    }
    .plan .button {
        display: inline-block;
        text-decoration: none;
        border: none;
        width: 100%;
        padding: 12px 35px;
        margin-top: 1rem;
        background-color: #6ab04c;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        text-align: center;
    }
    .plan.popular {
        border: 2px solid #6ab04c;
        position: relative;
        transform: scale(1.08);
    }
    .plan.popular span {
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #6ab04c;
        color: #fff;
        padding: 4px 20px;
        font-size: 18px;
        border-radius: 5px;
    }
    .plan:hover {
        box-shadow: 5px 7px 67px -28px rgba(0, 0, 0, 0.37);
    }
    </style>
    ";
}
add_action('wp_head', 'easy_pricing_table_styles');

// Admin-Menü für die Preistabellen-Einstellungen hinzufügen
add_action('admin_menu', 'easy_pricing_table_menu');

function easy_pricing_table_menu() {
    add_menu_page(
        'Preistabellen Einstellungen',
        'Preistabellen Einstellungen',
        'manage_options',
        'easy-pricing-table-settings',
        'easy_pricing_table_settings_page',
        'dashicons-money',
        20
    );
}

function easy_pricing_table_settings_page() {
    // Wenn das Formular abgeschickt wird, speichern wir die Preistabellen-Daten in der Option
    if (isset($_POST['easy_pricing_tables'])) {
        $pricing_tables = array();
        for ($i = 0; $i < 3; $i++) {
            $pricing_tables[] = array(
                'plan' => sanitize_text_field($_POST['easy_pricing_tables'][$i]['plan']),
                'price' => sanitize_text_field($_POST['easy_pricing_tables'][$i]['price']),
                'features' => sanitize_textarea_field($_POST['easy_pricing_tables'][$i]['features']),
                'link' => esc_url_raw($_POST['easy_pricing_tables'][$i]['link']),
            );
        }
        update_option('easy_pricing_tables', $pricing_tables);
        echo "<div class='updated'><p>Preistabellen gespeichert.</p></div>";
    }

    // Aktuelle Daten abrufen
    $pricing_tables = get_option('easy_pricing_tables', array_fill(0, 3, array('plan' => '', 'price' => '', 'features' => '', 'link' => '')));

    ?>
    <div class="wrap">
        <h1>Preistabellen Einstellungen</h1>
        <form method="post" action="">
            <?php for ($i = 0; $i < 3; $i++): ?>
                <h2>Preistabelle <?php echo ($i + 1); ?></h2>
                <label for="easy_pricing_tables_<?php echo $i; ?>_plan">Angebot:</label><br>
                <input type="text" id="easy_pricing_tables_<?php echo $i; ?>_plan" name="easy_pricing_tables[<?php echo $i; ?>][plan]" value="<?php echo esc_attr($pricing_tables[$i]['plan']); ?>" class="regular-text"><br><br>

                <label for="easy_pricing_tables_<?php echo $i; ?>_price">Preis:</label><br>
                <input type="text" id="easy_pricing_tables_<?php echo $i; ?>_price" name="easy_pricing_tables[<?php echo $i; ?>][price]" value="<?php echo esc_attr($pricing_tables[$i]['price']); ?>" class="regular-text"><br><br>

                <label for="easy_pricing_tables_<?php echo $i; ?>_features">Beschreibung (jeweils in einer neuen Zeile):</label><br>
                <textarea id="easy_pricing_tables_<?php echo $i; ?>_features" name="easy_pricing_tables[<?php echo $i; ?>][features]" rows="5" class="large-text"><?php echo esc_textarea($pricing_tables[$i]['features']); ?></textarea><br><br>

                <label for="easy_pricing_tables_<?php echo $i; ?>_link">Produktseite URL:</label><br>
                <input type="text" id="easy_pricing_tables_<?php echo $i; ?>_link" name="easy_pricing_tables[<?php echo $i; ?>][link]" value="<?php echo esc_url($pricing_tables[$i]['link']); ?>" class="regular-text"><br><br>
            <?php endfor; ?>
            <input type="submit" value="Speichern" class="button button-primary">
        </form>
    </div>
    <?php
}
