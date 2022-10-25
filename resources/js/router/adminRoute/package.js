import advertiserPackage from "../../view/admin/package/index";
import createPackage from "../../view/admin/package/createPackage";
import editPackage from "../../view/admin/package/editPackage";
import showPackage from "../../view/admin/package/showPackage";
import store from "../../store/admin";

export default [
    {
        path: 'package',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'package',
                component: advertiserPackage,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('package read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createPackage',
                component: createPackage,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('package create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editPackage',
                component: editPackage,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('package edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'show/:id(\\d+)',
                name: 'showPackage',
                component: showPackage,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('package show')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
