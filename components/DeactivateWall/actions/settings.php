<?php
$blocked_guids = input('blocked_guids');  // Die eingegebenen GUIDs

// Konvertiere die GUIDs in ein Array (entferne Leerzeichen, trenne nach Kommas)
$blocked_guids_array = array_map('trim', explode(',', $blocked_guids));

$component = new OssnComponents();
$settings  = $component->setSettings('DeactivateWall', array(
    'blocked_guids' => json_encode($blocked_guids_array),  // Speichern als JSON
));

if ($settings) {
    ossn_trigger_message(ossn_print('deactivatewall:settings:saved'), 'success');

        redirect(REF);
 
    
} else {
    ossn_trigger_message(ossn_print('deactivatewall:settings:error'), 'error');
    redirect(REF);  // Zurück auf die vorherige Seite, falls ein Fehler auftritt
}