import tippy from 'tippy.js';

(() => {
    // Add tippy to every element with the data-tippy-content attribute
    tippy(document.querySelectorAll('[data-tippy-content]'), {
        boundary: 'viewport',
        theme: 'ollieread',
    });
})();
