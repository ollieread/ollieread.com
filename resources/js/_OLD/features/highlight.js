import Vue from 'vue';

window.hljs = require('highlightjs');
window.hljs.initHighlightingOnLoad();

Vue.directive('highlightjs', {
    deep: true,
    bind: function (el, binding) {
        // on first bind, highlight all targets
        let targets = el.querySelectorAll('pre > code');
        targets.forEach((target) => {
            // if a value is directly assigned to the directive, use this
            // instead of the element content.
            if (binding.value) {
                target.textContent = binding.value;
            }
            window.hljs.highlightBlock(target);
        });
    },
    componentUpdated: function (el, binding) {
        // after an update, re-fill the content and then highlight
        let targets = el.querySelectorAll('pre > code');
        targets.forEach((target) => {
            if (binding.value) {
                target.textContent = binding.value;
                window.hljs.highlightBlock(target);
            }
        });
    },
});
