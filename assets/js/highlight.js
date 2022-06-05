import highlight from 'highlight.js/lib/core';
import json from 'highlight.js/lib/languages/json';

import 'highlight.js/styles/github.css';

highlight.registerLanguage('json', json);

highlight.highlightAll();
