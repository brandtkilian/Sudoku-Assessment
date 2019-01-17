
/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

import './bootstrap';
import Vue from 'vue';
import store from '@/js/stores'
import router from '@/js/router';
import App from '@/js/views/App';
import '@/js/components'

const app = new Vue({
  router,
  store,
  ...App
});

export default app;
