import indexAdOwner from "../../view/admin/adOwner/index";
import createAdOwner from "../../view/admin/adOwner/create";
import editAdOwner from "../../view/admin/adOwner/edit";
import store from "../../store/admin";

export default [
    {
        path: 'adOwner',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexAdOwner',
                component: indexAdOwner,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('adOwner read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createAdOwner',
                component: createAdOwner,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('adOwner create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editAdOwner',
                component: editAdOwner,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('adOwner edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
