import indexKayanPrice from "../../view/admin/kayanPrice/index";
import createKayanPrice from "../../view/admin/kayanPrice/create";
import editKayanPrice from "../../view/admin/kayanPrice/edit";
import store from "../../store/admin";

export default [
    {
        path: 'kayanPrice',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexKayanPrice',
                component: indexKayanPrice,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('kayanPrice read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                    return next();
                }
            },
            {
                path: 'create',
                name: 'createKayanPrice',
                component: createKayanPrice,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('kayanPrice create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                    return next();
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editKayanPrice',
                component: editKayanPrice,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('kayanPrice edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                    return next();
                }
            },
        ]
    },
];
