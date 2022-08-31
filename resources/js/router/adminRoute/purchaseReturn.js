import indexPurchaseReturn from "../../view/admin/purchaseReturn/index";
import createPurchaseReturn from "../../view/admin/purchaseReturn/create";
import editPurchaseReturn from "../../view/admin/purchaseReturn/edit";
import store from "../../store/admin";

export default [
    {
        path: 'PurchaseReturn',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexPurchaseReturn',
                component: indexPurchaseReturn,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('PurchaseReturn read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
