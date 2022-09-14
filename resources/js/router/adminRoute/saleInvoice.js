import indexSaleInvoice from "../../view/admin/saleInvoice/index";
import createSaleInvoice from "../../view/admin/saleInvoice/create";
import editSaleInvoice from "../../view/admin/saleInvoice/edit";
import store from "../../store/admin";

export default [
    {
        path: 'saleInvoice',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSaleInvoice',
                component: indexSaleInvoice,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('SaleInvoice read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createSaleInvoice',
                component: createSaleInvoice,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('SaleInvoice create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editSaleInvoice',
                component: editSaleInvoice,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('SaleInvoice edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
