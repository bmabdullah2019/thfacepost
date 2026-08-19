<?php
$component = new OssnComponents();
$settings = $component->getSettings('DeactivateWall');
$blocked_guids = '';

if ($settings && isset($settings->blocked_guids)) {
    // Dekodiere die gespeicherten GUIDs aus JSON
    $blocked_guids = implode(', ', json_decode($settings->blocked_guids));
}
?>

<div>
    <label>Gesperrte GUIDs</label>
    <textarea name="blocked_guids" placeholder="Tragen Sie hier die GUIDs ein, getrennt durch Kommas"><?php echo htmlspecialchars($blocked_guids); ?></textarea>
</div>

<input type="submit" class="btn btn-success" value="Speichern" />
