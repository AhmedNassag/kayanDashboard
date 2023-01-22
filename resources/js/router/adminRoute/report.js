import platformIncome from "../../view/admin/report/platformAccount/platformIncome.vue";
import platformExpense from "../../view/admin/report/platformAccount/platformExpense.vue";
import platformTransferringTreasury from "../../view/admin/report/platformAccount/platformTransferringTreasury.vue";
import platformIncomeTreasuries from "../../view/admin/report/platformAccount/platformIncomeTreasuries.vue";
import platformExpenseTreasuries from "../../view/admin/report/platformAccount/platformExpenseTreasuries.vue";
import platformDailyBalance from "../../view/admin/report/platformAccount/platformDailyBalance.vue";
import platformSuppliersDues from "../../view/admin/report/platformAccount/suppliersDues.vue";

import store from "../../store/admin";

export default [
    {
        path: 'report',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: 'platformIncome',
                name: 'platformIncome',
                component: platformIncome,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('platform-accounts-Report read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'platformExpense',
                name: 'platformExpense',
                component: platformExpense,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('platform-accounts-Report read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'platformTransferringTreasury',
                name: 'platformTransferringTreasury',
                component: platformTransferringTreasury,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('platform-accounts-Report read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'platformIncomeTreasuries',
                name: 'platformIncomeTreasuries',
                component: platformIncomeTreasuries,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('platform-accounts-Report read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'platformExpenseTreasuries',
                name: 'platformExpenseTreasuries',
                component: platformExpenseTreasuries,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('platform-accounts-Report read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'platformDailyBalance',
                name: 'platformDailyBalance',
                component: platformDailyBalance,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('platform-accounts-Report read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'platformSuppliersDues',
                name: 'platformSuppliersDues',
                component: platformSuppliersDues,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('platform-accounts-Report read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },

        ]
    },
];
