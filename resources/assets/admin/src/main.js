import Vue from 'vue';
import 'babel-polyfill';
import VueRouter from 'vue-router';
import Vuetify from 'vuetify';
import colors from 'vuetify/es5/util/colors';
import VeeValidate from 'vee-validate';

import App from './App.vue';
import './stylus/main.styl';

import routes from './router';

import mixin from './mixin';

import store from './store';


Vue.use(VeeValidate);

window.Vue = Vue;
const router = new VueRouter({
    routes, // short for `routes: routes`
});


Vue.use(Vuetify, {
    theme: {
        primary: colors.blueGrey.darken2,
        secondary: colors.blueGrey.lighten5,
        accent: colors.lightGreen.darken3,
        error: colors.red.base,
        warning: colors.yellow.darken1,
        info: colors.blue.darken1,
        success: colors.green.darken2,
    },
});

Vue.use(VueRouter);


const AppVue = new Vue({
    el: '#root',
    store,
    router,
    mixin,
    render: h => h(App),
});
