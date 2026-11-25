/**
 * Painel (layouts/app, sobre o AdminLTE). A ordem dos imports importa:
 * alerts.js registra a confirmação de envio antes do submit-once.js.
 */
import './bootstrap';
import '@fontsource-variable/inter';
import '../css/components/adminlte-overrides.css';
import '../css/components/ui.css';

import './components/theme-toggle';
import './components/alerts';
import './components/submit-once';
import './components/clipboard';
import './components/tooltips';
import './components/forms/select-value-toggle';
import './components/forms/cnpj-mask';
import './components/forms/select2-global';
import './components/forms/input-masks';

