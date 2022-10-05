import indexRegionReport from "../../view/admin/regionReport/index";
import createRegionReport from "../../view/admin/regionReport/create";
import editRegionReport from "../../view/admin/regionReport/edit";
import store from "../../store/admin";

export default [
    {
        path: 'regionReport',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexRegionReport',
                component: indexRegionReport,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('regionReport read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createRegionReport',
                component: createRegionReport,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('regionReport create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editRegionReport',
                component: editRegionReport,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('regionReport edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
