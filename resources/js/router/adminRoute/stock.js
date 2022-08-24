import indexStock from "../../view/admin/stock/index";
import createStock from "../../view/admin/stock/create";
import editStock from "../../view/admin/stock/edit";
import store from "../../store/admin";

export default [
    {
        path: 'stock',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexStock',
                component: indexStock,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('stock read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createStock',
                component: createStock,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('stock create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editStock',
                component: editStock,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('stock edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
