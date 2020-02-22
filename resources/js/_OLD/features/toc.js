(() => {
    document.querySelectorAll('.toc__header').forEach(element => {
        element.addEventListener('click', event => {
            if (element.parentElement.classList.contains('toc--expanded')) {
                element.parentElement.classList.remove('toc--expanded');
                element.parentElement.classList.add('toc--collapsed');
            } else {
                element.parentElement.classList.remove('toc--collapsed');
                element.parentElement.classList.add('toc--expanded');
            }
        })
    });
})();
