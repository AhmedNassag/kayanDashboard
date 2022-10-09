import indexAlternative from "../../view/admin/alternative/index";
import createAlternative from "../../view/admin/alternative/create";
import editAlternative from "../../view/admin/alternative/edit";
import store from "../../store/admin";

export default [
    {
        path: 'alternative',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexAlternative',
                component: indexAlternative,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('alternative read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createAlternative',
                component: createAlternative,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('alternative create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editAlternative',
                component: editAlternative,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('alternative edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
