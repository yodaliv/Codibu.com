window.Vue = require('vue');
Vue.component('recently-listed-component', require('./GenerateScoreButton.vue').default);
const app = new Vue({
    el : '#dashboard'
});
