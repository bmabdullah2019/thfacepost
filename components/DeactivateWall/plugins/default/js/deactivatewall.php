document.addEventListener('DOMContentLoaded', function() {
    // Verwende die Werte von currentUserGUID und blockedGUIDs aus dem globalen Kontext

    function replaceCommentBoxWithMessage(commentBox, message) {
        // Erstellt ein neues div-Element als Hinweis
        var messageElement = document.createElement('div');
        messageElement.className = 'comment-disabled-message';  // Klasse für Styling
        messageElement.innerText = Ossn.Print(message); // Verwende Ossn.Print für die Nachricht
        messageElement.style.color = 'red';
        messageElement.style.fontSize = '14px';
        messageElement.style.padding = '10px';

        // Ersetzt die Kommentarbox mit dem neuen Hinweis-Element
        commentBox.parentNode.replaceChild(messageElement, commentBox);
    }

    function hideCommentButtons() {
        // Selektiere alle Kommentar-Buttons und blende sie aus
        var commentButtons = document.querySelectorAll('.post-control-comment.comment-post');
        commentButtons.forEach(function(button) {
            button.style.display = 'none'; // Blendet den Button aus
        });
    }

    function removeGiphyIcons() {
        // Entfernt alle .giphy-icon Elemente
        var giphyIcons = document.querySelectorAll('.giphy-icon');
        giphyIcons.forEach(function(icon) {
            icon.remove(); // Entfernt das Element aus dem DOM
        });
    }

    function interceptSubmitButton(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();  // Verhindert das normale Absenden des Formulars
            alert(Ossn.Print('user:block:message'));
        });
    }

    // Überprüfen, ob die aktuelle GUID in der Liste der blockierten Benutzer ist
    if (blockedGUIDs.includes(currentUserGUID)) {
        // Hinweisnachricht für deaktivierte Kommentarboxen
        var disableMessageKey = 'comment:disabled:message'; // Schlüssel für Ossn.Print

        // Kommentar-Buttons ausblenden
        hideCommentButtons();

        // Giphy-Icons entfernen
        removeGiphyIcons();

        // Selektiere und ersetze nur die Kommentarboxen (.comment-box)
        var commentBoxes = document.querySelectorAll('.comment-box');
        commentBoxes.forEach(function(commentBox) {
            replaceCommentBoxWithMessage(commentBox, disableMessageKey);
        });

        var pinnedCommentBoxes = document.querySelectorAll('.pinned-post .comment-box');
        pinnedCommentBoxes.forEach(function(pinnedCommentBox) {
            replaceCommentBoxWithMessage(pinnedCommentBox, disableMessageKey);
        });

        // Interzeptiert den Submit-Button-Klick im .ossn-wall-container
        var submitButton = document.querySelector('.ossn-wall-container input[type="submit"]');
        if (submitButton) {
            interceptSubmitButton(submitButton);
        }

        // MutationObserver für dynamische Inhalte wie angepinnte Beiträge und Kommentare
        var observer = new MutationObserver(function(mutationsList) {
            mutationsList.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) {
                        // Sucht nach neuen Kommentarfeldern und deaktiviert sie
                        var newCommentBoxes = node.querySelectorAll('.comment-box');
                        newCommentBoxes.forEach(function(newCommentBox) {
                            replaceCommentBoxWithMessage(newCommentBox, disableMessageKey);
                        });

                        // Sucht nach neuen Kommentar-Buttons und blendet sie aus
                        var newCommentButtons = node.querySelectorAll('.post-control-comment.comment-post');
                        newCommentButtons.forEach(function(newButton) {
                            newButton.style.display = 'none';
                        });

                        // Entfernt neue Giphy-Icons
                        var newGiphyIcons = node.querySelectorAll('.giphy-icon');
                        newGiphyIcons.forEach(function(newGiphyIcon) {
                            newGiphyIcon.remove();
                        });
                    }
                });
            });
        });

        // Überwachen des gesamten Dokument-Body auf Veränderungen
        observer.observe(document.body, { childList: true, subtree: true });
    }
});
