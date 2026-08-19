<?php
/**
 * Open Source Social Network
 *
 * @package   DeactivateWall
 * @author    Dominik Lieger
 * @license   GPL v2 https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link      https://www.example.com
 */

define('__DEACTIVATEWALL__', ossn_route()->com . 'DeactivateWall/');

function deactivate_wall_init() {
    if (ossn_isAdminLoggedin()) {
        // Registrierung des Konfigurationspanels
        ossn_register_com_panel('DeactivateWall', 'settings');
        ossn_register_action('deactivatewall/admin/settings', __DEACTIVATEWALL__ . 'actions/settings.php');
    }

    // Wenn der Benutzer eingeloggt ist
    if (ossn_isLoggedin()) {
        $user_guid = ossn_loggedin_user()->guid;
        $component = new OssnComponents();
        $settings = $component->getSettings('DeactivateWall');
        $blocked_guids = isset($settings->blocked_guids) ? json_encode(json_decode($settings->blocked_guids)) : '[]';

        // Übergabe der GUID und blockierten GUIDs an JavaScript durch Einbettung direkt in ein <script>-Tag
        ossn_extend_view('ossn/site/head', function() use ($user_guid, $blocked_guids) {
            echo "<script>
                    var currentUserGUID = '{$user_guid}';
                    var blockedGUIDs = {$blocked_guids}; // blockedGUIDs ist bereits ein JSON-Array
                  </script>";
        });
    }

    // Erweiterung um JavaScript einzubinden
    ossn_extend_view('js/ossn.site', 'js/deactivatewall');
}

// Initialisiere die Komponente
ossn_register_callback('ossn', 'init', 'deactivate_wall_init');
