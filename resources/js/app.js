import { createApp } from 'vue/dist/vue.esm-bundler.js';
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routes';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const app = createApp({});

const router = createRouter({
    history: createWebHistory(),
    routes: Routes,
});

app.component('VueDatePicker', VueDatePicker);

app.use(router);
app.mount('#app');