import indexPrice from "../../view/admin/price/index";
import createPrice from "../../view/admin/price/create";
import editPrice from "../../view/admin/price/edit";
import store from "../../store/admin";

export default [
    {
        path: 'price',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexPrice',
                component: indexPrice,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    // if(permission.includes('price read')){
                    //     return next();
                    // }else{
                    //     return next({name:'Page404'});
                    // }
                    return next();
                }
            },
            {
                path: 'create',
                name: 'createPrice',
                component: createPrice,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    // if(permission.includes('price create')){
                    //     return next();
                    // }else{
                    //     return next({name:'Page404'});
                    // }
                    return next();
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editPrice',
                component: editPrice,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    // if(permission.includes('price edit')){
                    //     return next();
                    // }else{
                    //     return next({name:'Page404'});
                    // }
                    return next();
                }
            },
        ]
    },
];
