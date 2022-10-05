import indexClientReport from "../../view/admin/clientReport/index";
import createClientReport from "../../view/admin/clientReport/create";
import editClientReport from "../../view/admin/clientReport/edit";
import store from "../../store/admin";

export default [
    {
        path: 'clientReport',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexClientReport',
                component: indexClientReport,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('clientReport read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createClientReport',
                component: createClientReport,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('clientReport create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editClientReport',
                component: editClientReport,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('clientReport edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
