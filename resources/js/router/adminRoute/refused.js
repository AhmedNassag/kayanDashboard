import indexRefused from "../../view/admin/refused/index";
import createRefused from "../../view/admin/refused/create";
import editRefused from "../../view/admin/refused/edit";
import store from "../../store/admin";

export default [
    {
        path: 'refused',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexRefused',
                component: indexRefused,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('refused read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createRefused',
                component: createRefused,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('refused create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editRefused',
                component: editRefused,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('refused edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
