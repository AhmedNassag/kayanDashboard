import indexPurchase from "../../view/admin/purchase/index";
import createPurchase from "../../view/admin/purchase/create";
import editPurchase from "../../view/admin/purchase/edit";
import store from "../../store/admin";

export default [
    {
        path: 'purchase',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexPurchase',
                component: indexPurchase,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('purchase read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createPurchase',
                component: createPurchase,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('purchase create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editPurchase',
                component: editPurchase,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('purchase edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
