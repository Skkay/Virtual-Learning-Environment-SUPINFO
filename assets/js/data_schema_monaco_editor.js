import * as monaco from 'monaco-editor';

const monacoContainerEl = document.getElementById('monaco-container');
const submitButtonEl = document.getElementById('submitButton');
const content = monacoContainerEl.getAttribute('data-content');

const editor = monaco.editor.create(monacoContainerEl, {
    value: content,
    language: 'json',
});

submitButtonEl.addEventListener('click', () => {
    const formEl = document.getElementById('formDataSchema');
    const equivalenceInputEl = document.getElementById('data_schema_equivalence');

    equivalenceInputEl.value = editor.getValue();

    formEl.submit();
});
