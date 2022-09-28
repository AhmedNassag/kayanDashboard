import { createRouter, createWebHistory } from 'vue-router';
import Index from '../view/admin/Dashboard.vue';
import department from './adminRoute/department';
import productName from './adminRoute/productName';
import alternative from './adminRoute/alternative';
import product from './adminRoute/product';
import category from './adminRoute/category';
import subCategory from './adminRoute/subCategory';
import usersCategory from './adminRoute/usersCategory';
import tax from './adminRoute/tax';
import company from './adminRoute/company';
import pharmacistForm from './adminRoute/pharmacistForm';
import price from './adminRoute/price';
import kayanPrice from './adminRoute/kayanPrice';
import sellingMethod from './adminRoute/sellingMethod';
import shift from './adminRoute/shift';
import stock from './adminRoute/stock';
import virtualStock from './adminRoute/virtualStock';
import purchase from './adminRoute/purchase';
import saleInvoice from './adminRoute/saleInvoice';
import saleRecord from './adminRoute/saleRecord';
import saleReturn from './adminRoute/saleReturn';
import storage from './adminRoute/storage';
import refused from './adminRoute/refused';
import job from './adminRoute/job';
import role from './adminRoute/role';
import employee from './adminRoute/employee';
import notification from '../view/admin/notifications';
import Page404 from '../view/admin/Page404.vue';
import middlewarePipeline from "./middlewarePipeline";
import store from "../store/admin";
import guest from "../middleware/admin/guest";
import checkToken from "../middleware/admin/checkToken";
import auth from "../middleware/admin/auth";
import login from "../view/admin/login";
import forgetPassword from "../view/admin/forgetPassword";
import resetPassword from "../view/admin/resetPassword";
import unit from './adminRoute/unit';
import deal from './adminRoute/deal';
import unavailable_city_user from './adminRoute/unavailable-city-client';
import city from './adminRoute/city';
import area from './adminRoute/area';
import knowUsWay from './adminRoute/know-us-way';
import simpleAdvertise from './adminRoute/simple-advertise';
import slider from './adminRoute/slider';
import clientGroup from './adminRoute/client-group';
import offer from './adminRoute/offer';
import shipping from './adminRoute/shipping';
import supplier from './adminRoute/supplier';
import client from './adminRoute/client';
import newsletter from './adminRoute/newsletter';

import purchaseInvoice from './adminRoute/purchaseInvoice';
import examinationRecord from './adminRoute/examinationRecord';
import purchaseReturn from './adminRoute/purchaseReturn';
import salePoint from './adminRoute/sale-point';
const routes = [
    {
        path: '/',
        name: 'loginLang',
        component: login,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/login',
        name: 'login',
        component: login,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/forget-password',
        name: 'forgetPassword',
        component: forgetPassword,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/reset-password',
        name: 'resetPassword',
        component: resetPassword,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/dashboard',
        name: 'partner',
        component: {
            template: '<router-view />',
        },
        meta: {
            middleware: [auth, checkToken]
        },
        children: [
            {
                path: '',
                name: 'dashboard',
                component: Index,
            },
            {
                path: 'notification',
                name: 'notification',
                component: notification,
            },
            ...department,
            ...job,
            ...employee,
            ...role,
            ...pharmacistForm,
            ...category,
            ...subCategory,
            ...usersCategory,
            ...tax,
            ...company,
            ...productName,
            ...alternative,
            ...product,
            ...price,
            ...kayanPrice,
            ...sellingMethod,
            ...shift,
            ...stock,
            ...slider,
            ...simpleAdvertise,
            ...virtualStock,
            ...purchase,
            ...saleInvoice,
            ...saleRecord,
            ...saleReturn,
            ...storage,
            ...refused,
            ...unit,
            ...deal,
            ...unavailable_city_user,
            ...city,
            ...area,
            ...knowUsWay,
            ...newsletter,
            ...offer,
            ...shipping,
            ...supplier,
            ...clientGroup,
            ...client,
            ...purchaseInvoice,
            ...examinationRecord,
            ...purchaseReturn,
            ...salePoint
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'Page404',
        component: Page404
    },
];

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes
});


router.beforeEach((to, from, next) => {

    if (!to.meta.middleware) return next();
    const middleware = to.meta.middleware;
    const context = {
        to,
        from,
        next,
        store
    };
    return middleware[0]({
        ...context,
        next: middlewarePipeline(context, middleware, 1)
    });
});

export default router;
