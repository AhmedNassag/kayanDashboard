import indexSaleMethod from "../../view/admin/saleMethod/index";
import createSaleMethod from "../../view/admin/saleMethod/create";
import editSaleMethod from "../../view/admin/saleMethod/edit";
import store from "../../store/admin";

export default [
    {
        path: 'saleMethod',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSaleMethod',
                component: indexSaleMethod,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('saleMethods read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createSaleMethod',
                component: createSaleMethod,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('saleMethods create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editSaleMethod',
                component: editSaleMethod,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('saleMethods edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
