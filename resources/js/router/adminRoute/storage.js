import indexStorage from "../../view/admin/storage/index";
import createStorage from "../../view/admin/storage/create";
import editStorage from "../../view/admin/storage/edit";
import store from "../../store/admin";

export default [
    {
        path: 'storage',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexStorage',
                component: indexStorage,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('storage read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createStorage',
                component: createStorage,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('storage create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editStorage',
                component: editStorage,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('storage edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
