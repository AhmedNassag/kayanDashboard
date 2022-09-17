import CityIndex from "../../view/admin/city/index";
import store from "../../store/admin";
export default [
    {
        path: 'cities',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'CityIndex',
                component: CityIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('city read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
