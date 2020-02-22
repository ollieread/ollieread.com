(() => {
    document.querySelectorAll('.tabs').forEach(element => {
        let tabs  = element.querySelectorAll('.tab');

        tabs.forEach(tab => {
            tab.addEventListener('click', event => {
                element.querySelectorAll('.tab--active')
                       .forEach(otherTab => otherTab.classList.remove('tab--active'));
                tab.classList.add('tab--active');

                let content = element.querySelectorAll('[data-name=' + tab.dataset.target + ']').item(0);

                if (content) {
                    element.querySelectorAll('.tab__content')
                           .forEach(otherContent => otherContent.classList.remove('tab__content--active'));
                    content.classList.add('tab__content--active');
                }
            });
        });
    });
})();