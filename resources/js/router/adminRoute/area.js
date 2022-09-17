import AreaIndex from "../../view/admin/area/index";
import store from "../../store/admin";
export default [
    {
        path: 'areas',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'AreaIndex',
                component: AreaIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('area read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
