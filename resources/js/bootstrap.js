import axios from 'axios';

// Define o axios na janela global
window.axios = axios;

// Verifica se o axios e o objeto defaults existem antes de configurar
if (window.axios && window.axios.defaults) {
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}