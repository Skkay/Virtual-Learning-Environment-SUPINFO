import * as monaco from 'monaco-editor';
import { Toast } from 'bootstrap/dist/js/bootstrap.esm';

const monacoContainerEl = document.getElementById('monaco-container');
const submitButtonEl = document.getElementById('submitButton');
const toastErrorEl = document.getElementById('toastErrorDataSchema');
const toastErrorBodyEl = document.getElementById('toastErrorDataSchemaBody');
const content = monacoContainerEl.getAttribute('data-content');

const editor = monaco.editor.create(monacoContainerEl, {
    value: content,
    language: 'json',
});

const getErrors = () => {
    const markers = monaco.editor.getModelMarkers();
    const errors = markers.filter((marker) => marker.severity === 8);

    return errors;
};

const hasErrors = () => getErrors().length !== 0;

const submitForm = () => {
    const formEl = document.getElementById('formDataSchema');
    const equivalenceInputEl = document.getElementById('data_schema_equivalence');

    equivalenceInputEl.value = editor.getValue();

    if (hasErrors()) {
        let toastBody = '';
        getErrors().forEach((error) => {
            toastBody += `- ${error.message} (line: ${error.startLineNumber}, column: ${error.startColumn})\n`;
        });

        toastErrorBodyEl.innerText = toastBody;

        const toastError = new Toast(toastErrorEl);
        toastError.show();

        return;
    }

    formEl.submit();
};

submitButtonEl.addEventListener('click', submitForm);
