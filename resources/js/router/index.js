import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import DashBoard from '../pages/Dashboard';
import VerifyEmail from '../pages/auth/VerifyEmail';
import ForgotPassword from '../pages/auth/ForgotPassword';
import ForgotPasswordEmail from '../pages/auth/ForgotPasswordEmail';

const routes = [
    {
        path: '/',
        component: DashBoard,
        name: 'dashboard'
    },
    {
        path: '/asd',
        component: DashBoard,
        name: 'asd'
    },

    {
        name: 'verify-email',
        path: '/email/verify',
        component:VerifyEmail,
    },
    {
        name: 'forgot-password',
        path: '/forgot-password',
        component: ForgotPassword,

    },
    {
      name:'password-reset',
      path:'/profile/forgot-password-reset/:token/:email',
      component: ForgotPasswordEmail,
    },
    {
        path: "*",
        component: DashBoard
    },


];

export default new VueRouter({
    mode: 'history',
    routes
})
