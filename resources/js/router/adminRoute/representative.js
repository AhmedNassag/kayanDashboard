import indexRepresentative from "../../view/admin/representative/index.vue";
import createRepresentative from "../../view/admin/representative/create.vue";
import editRepresentative from "../../view/admin/representative/edit.vue";
import changePasswordRepresentative from "../../view/admin/representative/changePassword.vue";
import representative_profile from "../../view/admin/representative/representative_profile.vue";
import store from "../../store/admin";

export default [
    {
        path: 'representative',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexRepresentative',
                component: indexRepresentative,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('representative read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'representative_profile/:id(\\d+)',
                name: 'representative_profile',
                component: representative_profile,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('representative read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createRepresentative',
                component: createRepresentative,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('representative create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editRepresentative',
                component: editRepresentative,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('representative edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'changePassword/:id(\\d+)',
                name: 'changePasswordRepresentative',
                component: changePasswordRepresentative,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('representativeChangePassword edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
