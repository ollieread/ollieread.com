(() => {
    let sidebarOpenButton = document.getElementById('sidebar__open');

    if (sidebarOpenButton) {
        sidebarOpenButton.addEventListener('click', (e) => {
            let sidebar = document.getElementsByClassName('sidebar').item(0);

            if (sidebar) {
                if (!sidebar.classList.contains('sidebar--open')) {
                    sidebar.classList.add('sidebar--open');
                }
            }
        });
    }

    let sidebarCloseButton = document.getElementById('sidebar__close');

    if (sidebarCloseButton) {
        sidebarCloseButton.addEventListener('click', (e) => {
            let sidebar = document.getElementsByClassName('sidebar').item(0);

            if (sidebar) {
                if (sidebar.classList.contains('sidebar--open')) {
                    sidebar.classList.remove('sidebar--open');
                }
            }
        });
    }
})()