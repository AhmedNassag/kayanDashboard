import ShippingIndex from "../../view/admin/shipping/index";
import store from "../../store/admin";
export default [
    {
        path: 'shippings',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'ShippingIndex',
                component: ShippingIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('shipping read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
