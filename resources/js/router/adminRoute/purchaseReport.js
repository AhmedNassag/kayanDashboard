import indexPurchaseReport from "../../view/admin/purchaseReport/index";
import createPurchaseReport from "../../view/admin/purchaseReport/create";
import editPurchaseReport from "../../view/admin/purchaseReport/edit";
import store from "../../store/admin";

export default [
    {
        path: 'purchaseReport',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexPurchaseReport',
                component: indexPurchaseReport,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('purchaseReport read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createPurchaseReport',
                component: createPurchaseReport,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('purchaseReport create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editPurchaseReport',
                component: editPurchaseReport,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('purchaseReport edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
