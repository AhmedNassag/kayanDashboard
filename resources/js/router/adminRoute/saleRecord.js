import indexSaleRecord from "../../view/admin/SaleRecord/index";
import createSaleRecord from "../../view/admin/SaleRecord/create";
import editSaleRecord from "../../view/admin/SaleRecord/edit";
import store from "../../store/admin";

export default [
    {
        path: 'saleRecord',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSaleRecord',
                component: indexSaleRecord,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('saleRecords read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/:id(\\d+)',
                name: 'createSaleRecord',
                component: createSaleRecord,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('saleRecords create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editSaleRecord',
                component: editSaleRecord,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('saleRecords edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
