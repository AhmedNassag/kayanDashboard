import indexSaleReturn from "../../view/admin/saleReturn/index";
import createSaleReturn from "../../view/admin/saleReturn/create";
import editSaleReturn from "../../view/admin/saleReturn/edit";
import store from "../../store/admin";

export default [
    {
        path: 'saleReturn',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSaleReturn',
                component: indexSaleReturn,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('SaleReturn read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
