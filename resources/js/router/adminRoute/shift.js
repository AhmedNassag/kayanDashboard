import indexShift from "../../view/admin/shift/index";
import createShift from "../../view/admin/shift/create";
import editShift from "../../view/admin/shift/edit";
import store from "../../store/admin";

export default [
    {
        path: 'shift',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexShift',
                component: indexShift,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('shift read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createShift',
                component: createShift,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('shift create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editShift',
                component: editShift,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('shift edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
