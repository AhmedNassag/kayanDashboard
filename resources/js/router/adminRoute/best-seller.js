import BestSellerIndex from "../../view/admin/best-seller/index";
import store from "../../store/admin";

export default [
    {
        path: 'best-seller',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'BestSellerIndex',
                component: BestSellerIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('best-seller read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
