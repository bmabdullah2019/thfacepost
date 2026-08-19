$(document).ready(function() {
    // Suche nach dem Button mit der Klasse .ossn-message-delete-conversation
    var deleteButton = $('.ossn-message-delete-conversation');
    
    // Überprüfe, ob der Button vorhanden ist
    if(deleteButton.length) {
        // Erstelle einen neuen Button-Element für "Back" und füge die spezielle Klasse .msg-button-back hinzu
        var backButton = $('<button>', {
            text: Ossn.Print('button:back'), // Verwende Ossn.Print() für die Übersetzung des "Back"-Texts
            class: 'btn btn-default msg-button-back', // CSS-Klassen für das Styling, inkl. spezieller Klasse 'msg-button-back'
            click: function() {
                // Funktion, die ausgeführt wird, wenn der Button geklickt wird
                window.location.href = Ossn.site_url + 'messages'; // Zurück zur Nachrichtenübersicht
            }
        });
        
        // Füge den Back-Button links neben dem Delete-Button ein
        deleteButton.before(backButton);
    }
});
