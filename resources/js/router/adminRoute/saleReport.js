import indexSaleReport from "../../view/admin/saleReport/index";
import store from "../../store/admin";

export default [
    {
        path: 'saleReport',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSaleReport',
                component: indexSaleReport,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('saleReport read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
