import MostPopularIndex from "../../view/admin/most-popular/index";
import store from "../../store/admin";

export default [
    {
        path: 'most-popular',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'MostPopularIndex',
                component: MostPopularIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('most-popular read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
