import SupplierIndex from "../../view/admin/suppliers/index";
import store from "../../store/admin";
export default [
    {
        path: 'suppliers',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: 'suppliers',
                name: 'SupplierIndex',
                component: SupplierIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('supplier read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
