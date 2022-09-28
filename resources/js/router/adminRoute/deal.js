import DealIndex from "../../view/admin/deal/index";
import store from "../../store/admin";
export default [
    {
        path: 'deal',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'DealIndex',
                component: DealIndex,
                // beforeEnter: (to, from, next) => {
                //     let permission = store.state.authAdmin.permission;
                //     if (permission.includes('deal insert')) {
                //         return next();
                //     } else {
                //         return next({ name: 'Page404' });
                //     }
                // }
            },
        ]
    },
];
