import indexDelegateReport from "../../view/admin/delegateReport/index";
import createDelegateReport from "../../view/admin/delegateReport/create";
import editDelegateReport from "../../view/admin/delegateReport/edit";
import store from "../../store/admin";

export default [
    {
        path: 'delegateReport',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexDelegateReport',
                component: indexDelegateReport,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('delegateReport read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createDelegateReport',
                component: createDelegateReport,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('delegateReport create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editDelegateReport',
                component: editDelegateReport,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('delegateReport edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
