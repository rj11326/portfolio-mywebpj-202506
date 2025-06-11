require('./bootstrap');

const Alpine = require('alpinejs').default;
window.Alpine = Alpine;
document.addEventListener('DOMContentLoaded', () => {
    Alpine.start();
});