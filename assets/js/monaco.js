import * as monaco from 'monaco-editor';

monaco.editor.create(document.getElementById('container'), {
    value: ['{', '\t"test": true', '}'].join('\n'),
    language: 'json',
});
