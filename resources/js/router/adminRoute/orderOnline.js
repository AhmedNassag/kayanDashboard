import indexOrderOnline from "../../view/admin/orderOnline/index.vue";
import collect_orders_per_day from "../../view/admin/orderOnline/collect_orders_per_day.vue";
import collect_orders_per_day_for_each_client from "../../view/admin/orderOnline/collect_orders_per_day_for_each_client.vue";
import showOrderOnline from "../../view/admin/orderOnline/show";
import store from "../../store/admin";
// import showSuggestionClient from "../../view/admin/suggestionClient/show";

export default [
    {
        path: 'orderOnline',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexOrderOnline',
                component: indexOrderOnline,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('orderOnline read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'show/:id(\\d+)',
                name: 'showOrderOnline',
                component: showOrderOnline,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('orderOnline read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
    {
        path: 'collect_orders_per_day',
        name: 'CollectOrdersPerDay',
        component: collect_orders_per_day,
        props: true,
        beforeEnter: (to, from,next) => {
            let permission = store.state.authAdmin.permission;

            if(permission.includes('CollectOrdersPerDay read')){
                return next();
            }else{
                return next({name:'Page404'});
            }
        }
    },
    {
        path: 'collect_orders_per_day_for_each_client',
        name: 'collect_orders_per_day_for_each_client',
        component: collect_orders_per_day_for_each_client,
        props: true,
        beforeEnter: (to, from,next) => {
            let permission = store.state.authAdmin.permission;

            if(permission.includes('CollectOrdersPerDay read')){
                return next();
            }else{
                return next({name:'Page404'});
            }
        }
    },
];
