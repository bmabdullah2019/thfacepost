document.addEventListener('DOMContentLoaded', function() {
    var menu = document.querySelector('.profile-hr-menu');
    if (menu) {
        var editProfileLink = document.createElement('a');
        editProfileLink.href = window.location.href + '/edit';
        editProfileLink.innerText = Ossn.Print('profile:edit');
        editProfileLink.classList.add('edit-profile-link');
        
        var menuItem = document.createElement('li');
        menuItem.appendChild(editProfileLink);
        menu.appendChild(menuItem);

        var currentUrl = window.location.href;
        if (currentUrl.includes('/u/<?php echo ossn_loggedin_user()->username; ?>')) {
            editProfileLink.href = 'https://queermeet.de/u/<?php echo ossn_loggedin_user()->username; ?>/edit';
        }
    }
});
