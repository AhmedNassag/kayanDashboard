import indexVirtualStock from "../../view/admin/virtualStock/index";
import createVirtualStock from "../../view/admin/virtualStock/create";
import editVirtualStock from "../../view/admin/virtualStock/edit";
import store from "../../store/admin";

export default [
    {
        path: 'virtualStock',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexVirtualStock',
                component: indexVirtualStock,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('virtualStock read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/:id(\\d+)',
                name: 'createVirtualStock',
                component: createVirtualStock,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('virtualStock create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editVirtualStock',
                component: editVirtualStock,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('stock edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
