<?php
/*
Plugin Name: Easy Pricing Table
Plugin URI: https://torbenb.info/download/
Description: Erstelle einfache Preistabellen mit dem Shortcode [easy_pricing_table].
Version: 1.2
Author: TorbenB
Author URI: https://torbenb.info/
*/

// Funktion, um die Preistabelle anzuzeigen
function easy_pricing_table_shortcode() {
    // HTML-Layout für die Preistabelle
    $output = '
    <div class="pricing-table">
        <table>
            <thead>
                <tr>
                    <th>Plan</th>
                    <th>Preis</th>
                    <th>Leistungen</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic</td>
                    <td>€9.99/Monat</td>
                    <td>1 Benutzer, 5 GB Speicher</td>
                </tr>
                <tr>
                    <td>Pro</td>
                    <td>€19.99/Monat</td>
                    <td>5 Benutzer, 50 GB Speicher</td>
                </tr>
                <tr>
                    <td>Business</td>
                    <td>€29.99/Monat</td>
                    <td>Unbegrenzte Benutzer, 200 GB Speicher</td>
                </tr>
            </tbody>
        </table>
    </div>
    ';

    // Rückgabe der Preistabelle
    return $output;
}

// Shortcode registrieren
add_shortcode('easy_pricing_table', 'easy_pricing_table_shortcode');

// CSS für die Preistabelle hinzufügen
function easy_pricing_table_styles() {
    echo "
    <style>
    .pricing-table table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    .pricing-table th, .pricing-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    .pricing-table th {
        background-color: #f4f4f4;
        font-weight: bold;
    }
    .pricing-table td {
        font-size: 16px;
    }
    </style>
    ";
}
add_action('wp_head', 'easy_pricing_table_styles');
