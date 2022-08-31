import indexProductName from "../../view/admin/productName/index";
import createProductName from "../../view/admin/productName/create";
import editProductName from "../../view/admin/productName/edit";
import store from "../../store/admin";

export default [
    {
        path: 'productName',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexProductName',
                component: indexProductName,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('productName read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createProductName',
                component: createProductName,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('productName create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editProductName',
                component: editProductName,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('productName edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
