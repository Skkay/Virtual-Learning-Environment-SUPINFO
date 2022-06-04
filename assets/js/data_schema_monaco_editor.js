import * as monaco from 'monaco-editor';

const monacoContainerEl = document.getElementById('monaco-container');
const submitButtonEl = document.getElementById('submitButton');
const content = monacoContainerEl.getAttribute('data-content');

const hasNoError = () => {
    const markers = monaco.editor.getModelMarkers();
    const errors = markers.filter((marker) => marker.severity === 8);

    return errors.length === 0;
};

const editor = monaco.editor.create(monacoContainerEl, {
    value: content,
    language: 'json',
});

submitButtonEl.addEventListener('click', () => {
    const formEl = document.getElementById('formDataSchema');
    const equivalenceInputEl = document.getElementById('data_schema_equivalence');

    equivalenceInputEl.value = editor.getValue();

    if (hasNoError()) {
        formEl.submit();
    }
});
