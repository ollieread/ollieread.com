import SimpleMDE from 'simplemde';

(() => {
    document.querySelectorAll('[data-provides=markdown]').forEach(element => {
        new SimpleMDE({
            element,
            autosave: {
                enabled: false,
            },
            spellChecker: false,
            indentWithTabs: false,
            autoDownloadFontAwesome: false,
            renderingConfig: {
                codeSyntaxHighlighting: true,
            },
            toolbar: ['preview'],
        })
    })
})();
