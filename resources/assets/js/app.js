import MuseUI from 'muse-ui'
import 'muse-ui/dist/muse-ui.css'

import axios from 'axios'
import VueAxios from 'vue-axios'
import Vuex from 'vuex'

import 'iview/dist/styles/iview.css'
// import './my-themes/index.less'
import iView from 'iview'

window.Vue = require('vue');
// require('./bootstrap');

Vue.use(MuseUI);
Vue.use(iView);
Vue.use(VueAxios, axios);
Vue.use(Vuex)

Vue.config.productionTip = false

// // Vuex 配置
const store = new Vuex.Store({
    state: {
        all: [],
        Cards: [],
        NoCards: [],
    },
    getters:{
        // 参数列表state指的是state数据
        getCards(state){
            return state.Cards;
        },
        getNoCards(state){
            return state.NoCards;
        },
        getAll(state){
            return state.all;
        }
    },
    actions: {
        setLost({commit,state}, all, Cards, NoCards){
            // 跟后台打交道
            // 调用mutaions里面的方法
            commit("setData", all, Cards, NoCards);
        }
    },
    mutations: {
        setData (state, all, Cards, NoCards) {
            state.all = all;
            state.Cards = Cards;
            state.NoCards = NoCards;
        }
    },
})

// Vue.component('super', require('./components/superAdmin.vue'));
// Vue.component('uploadlost', require('./components/adminLost.vue'));
// Vue.component('thelosttaken', require('./components/adminLost_recipient.vue'));
Vue.component('index', require('./components/index.vue'));
// Vue.component('found', require('./components/found.vue'));

const app = new Vue({
    components: {
        super: resolve => {
            require(['./components/superAdmin.vue'], resolve)
        },
    },
    el: '#app',
    store
});
