import SalePointIndex from "../../view/admin/sale-point/index";
import store from "../../store/admin";
export default [
    {
        path: 'sales-points',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'SalePointIndex',
                component: SalePointIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('sale-point read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
