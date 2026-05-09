import axios from 'axios';
import { Ziggy } from './ziggy';

// Configura o axios para usar as rotas do Laravel
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configura o Ziggy para as rotas
window.Ziggy = Ziggy;
