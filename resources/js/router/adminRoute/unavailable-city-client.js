import UnvailableCityClientIndex from "../../view/admin/unavailable-city-client/index";
import store from "../../store/admin";
export default [
    {
        path: 'unavailable-cities-clients',
        component: {
            template: '<router-view />',
        },
        children: [
            {
                path: '',
                name: 'UnvailableCityClientIndex',
                component: UnvailableCityClientIndex,
                beforeEnter: (to, from, next) => {
                    let permission = store.state.authAdmin.permission;
                    if (permission.includes('unavailable-city-client read')) {
                        return next();
                    } else {
                        return next({ name: 'Page404' });
                    }
                }
            },
        ]
    },
];
