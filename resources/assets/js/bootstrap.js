
import Vue from 'vue';

import VueRouter from 'vue-router';

import axios from 'axios';



window.Vue = Vue;

Vue.use(VueRouter);

window.axios = axios;
require('vue-resource');
require('vue-events');

Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', Laravel.csrfToken);
    next();
});

window.axios.defaults.headers.common = {
	'X-Requested-With': 'XMLHttpRequest'
};

