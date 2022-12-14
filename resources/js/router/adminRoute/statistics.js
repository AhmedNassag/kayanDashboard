import client_doesnt_have_orders from "../../view/admin/statistics/client_doesnt_have_orders";
import client_has_cart from "../../view/admin/statistics/client_has_cart";
import web_clients from "../../view/admin/statistics/web_clients";
import client_profile from "../../view/admin/statistics/client_profile";
import supplier_profile from "../../view/admin/statistics/supplier_profile";

export default [
    {
        path: 'client_doesnt_have_orders',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'client_doesnt_have_orders',
                component: client_doesnt_have_orders,
            }
        ]
    },
    {
        path: 'client_has_cart',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'client_has_cart',
                component: client_has_cart,
            }
        ]
    },
    {
        path: 'web_clients',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'web_clients',
                component: web_clients,
            }
        ]
    },
    {
        path: 'client_profile/:id(\\d+)',
        props: true,

        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'client_profile',
                component: client_profile,
            }
        ]
    },
    {
        path: 'supplier_profile/:id(\\d+)',
        props: true,

        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'supplier_profile',
                component: supplier_profile,
            }
        ]
    },
];
