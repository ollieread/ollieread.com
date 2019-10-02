import Choices from 'choices.js';

(() => {
    if (document.querySelectorAll('[data-provides=choices]').length > 0) {
        let choices = new Choices('[data-provides=choices]');
    }
})();
