import KnowUsWayIndex from "../../view/admin/know-us-ways/index";
import store from "../../store/admin";
export default [
    {
        path: 'know-us-ways',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'KnowUsWayIndex',
                component: KnowUsWayIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('know-us-way read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
