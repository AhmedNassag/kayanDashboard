import indexStockReport from "../../view/admin/stockReport/index";
import createStockReport from "../../view/admin/stockReport/create";
import editStockReport from "../../view/admin/stockReport/edit";
import store from "../../store/admin";

export default [
    {
        path: 'stockReport',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexStockReport',
                component: indexStockReport,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('stockReport read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createStockReport',
                component: createStockReport,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('stockReport create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editStockReport',
                component: editStockReport,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('stockReport edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
