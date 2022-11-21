import indexAlternativePrice from "../../view/admin/alternativePrice/index";
import createAlternativePrice from "../../view/admin/alternativePrice/create";
import editAlternativePrice from "../../view/admin/alternativePrice/edit";
import store from "../../store/admin";

export default [
    {
        path: 'alternativePrice',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexAlternativePrice',
                component: indexAlternativePrice,
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
                name: 'createAlternativePrice',
                component: createAlternativePrice,
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
                name: 'editAlternativePrice',
                component: editAlternativePrice,
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
