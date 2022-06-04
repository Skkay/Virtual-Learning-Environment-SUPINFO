import * as monaco from 'monaco-editor';

const monacoContainerEl = document.getElementById('monaco-container');
const content = monacoContainerEl.getAttribute('data-content');

monaco.editor.create(monacoContainerEl, {
    value: content,
    language: 'json',
});
