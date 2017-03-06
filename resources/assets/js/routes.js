'use strict';

import VueRouter from 'vue-router';

export default new VueRouter({
    mode: 'history',
    routes: [{
        path: '/',
        component: require('./views/Home')
    }, {
        path: '/game',
        component: require('./views/Game')
    }, {
        path: '/leaderboard',
        component: require('./views/Leaderboard')
    }]
});