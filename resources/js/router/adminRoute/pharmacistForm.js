import indexPharmacistForm from "../../view/admin/pharmacistForm/index";
import createPharmacistForm from "../../view/admin/pharmacistForm/create";
import editPharmacistForm from "../../view/admin/pharmacistForm/edit";
import store from "../../store/admin";

export default [
    {
        path: 'pharmacistForm',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexPharmacistForm',
                component: indexPharmacistForm,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('pharmacistForm read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createPharmacistForm',
                component: createPharmacistForm,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('pharmacistForm create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editPharmacistForm',
                component: editPharmacistForm,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('pharmacistForm edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
